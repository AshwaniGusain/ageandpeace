<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;

class Membership extends Model
{
    use SoftDeletes;

    protected $with = [
        'type'
    ];

    protected $fillable = [
        'provider_id',
        'membership_type_id',
        'expiration_date'
    ];

    protected $dates = ['deleted_at'];

    public function type()
    {
        return $this->belongsTo('\App\Models\MembershipType', 'membership_type_id');
    }

	public function provider(){
		return $this->belongsTo( '\App\Models\Provider', 'provider_id');
	}

    public function getDisplayNameAttribute()
    {
        return $this->type->tier;
    }
}
