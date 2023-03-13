<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;

class MembershipType extends Model
{
    use SoftDeletes;
    use HasActive;

    protected $fillable = [
        'tier',
        'description',
        'price',
        'term_length',
        'active'
    ];

    protected $sanitization = [
        'price' => 'clean_numbers'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($membership_type) {
            foreach ($membership_type->memberships as $membership) {
                $membership->membership_type_id = null;
                $membership->save();
            }
        });
    }

    public function memberships()
    {
        return $this->hasMany('App\Models\Membership', 'membership_type_id');
    }

    public function providers()
    {
        return $this->hasMany('App\Models\Provider', 'membership_type_id');
    }

    public function issueMembership(Provider $provider)
    {
        $expirationDate = Carbon::parse($this->term_length)->format('Y-m-d');

        $this->memberships()->create([
            'provider_id'     => $provider->id,
            'expiration_date' => $expirationDate
        ]);

        return $provider;
    }

    public function getDisplayNameAttribute()
    {
        return $this->tier;
    }

}
