<?php

namespace Snap\Admin\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Str;
use Snap\Admin\Notifications\ResetPassword as SnapResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\HasDisplayName;
use Snap\Database\Model\Traits\SoftDeleteHelper;
use Snap\Database\Model\Traits\IsSearchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    // Laravel specific.
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    // SNAP specific.
    use HasActive;
    use HasDisplayName;
    use IsSearchable;
    use SoftDeleteHelper;

    //protected static $useActive = false;
    //protected static $unique = ['email' => 'user_id'];
    public static $rules = [
        'name'  => 'required',
        //'email' => 'required|email',
        'email' => 'required|email|unique:snap_users,email,{id}',
        //'password' => 'min:8',
    ];

    protected $displayNameField = 'name';

    protected $dates = ['deleted_at', 'last_login_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'active',
        'last_login_ip',
        'last_login_at',
    ];

    protected $sanitization = [
        '*' => 'safe_htmlentities',
    ];

    protected $guard_name = 'web';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //protected $appends = [
    //    'role_names',
    //    'is_super_admin'
    //];

    protected $table = 'snap_users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function($user) {
            // Password is a nullable field and we don't expose it on the front end
            // so we'll auto-generate a password for them.
            if (empty($user->password)) {
                // Use the Hash facade instead of the bcrypt function just in case
                // the application wants to use a different Laravel encryption method.
                $user->password = \Hash::make(Str::random(16));
            }
        });

        static::updating(function($user){
            // Super admin's cannot be deactivated
            if ($user->hasRole('super-admin')) {
                $user->active = 1;
                $user->addError('Cannot deactivate a super-admin user');
            }
        });

        static::deleting(function($user){
            if ($user->hasRole('super-admin')) {
                return false;
            }
        });
    }

    /**
     * Send the password reset notification.
     *
     * @overwrite to allow for different path for password reset.
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SnapResetPasswordNotification($token));
    }

    public function getRoleNamesAttribute()
    {
        return $this->roles()->pluck('name');
    }

    public function getNameEmailAttribute()
    {
        return $this->name.' ('.$this->email.')';
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->isSuperAdmin();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
