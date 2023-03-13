<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Snap\Admin\Models\User as SnapUserModel;
use Snap\Admin\Notifications\ResetPassword as SnapResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
class User extends SnapUserModel
{
    public function customer(): HasOne
    {
        return $this->hasOne('App\Models\Customer');
    }

    public function isCustomer()
    {
        return ($this->customer->id) ?? false;
    }

    //public function setPasswordAttribute($password)
    //{
    //    $this->attributes['password'] = bcrypt($password);
    //    return $this;
    //}

    public function provider(): HasOne
    {
        return $this->hasOne('App\Models\Provider');
    }

    public function isProvider()
    {
        return ($this->provider->id) ?? false;
    }

    public function scopeActive($builder)
    {
        return $builder->where('active', 1);
    }

    public function scopeInActive($builder)
    {
        return $builder->where('active', 0);
    }

    public function getAgeAttribute()
    {
        return $this->customer ? $this->customer->age : false;
    }

    public function getZipAttribute()
    {
        return $this->customer ? $this->customer->zip : false;
    }

    public function getIsActiveFriendlyAttribute()
    {
        if ($this->active) {
            return 'yes';
        }

        return 'no';
    }

    public function getValidEmailAttribute()
    {
        if (strpos($this->email, 'invalid@') === false) {
            return $this->email;
        }

        return null;
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
        if ($this->hasRole('admin')) {
            $this->notify(new SnapResetPasswordNotification($token));
        } else {
            $this->notify(new ResetPasswordNotification($token));
        }
    }
}
