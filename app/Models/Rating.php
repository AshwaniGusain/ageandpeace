<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;

class Rating extends Model
{
    use SoftDeletes;

    protected $displayNameField = 'name_rating';

    protected $fillable = ['customer_id', 'provider_id', 'rating'];

    protected $dates = ['deleted_at'];


    protected $with = [
        'provider',
        'customer'
    ];

    public function provider(): belongsTo
    {
        return $this->belongsTo('\App\Models\Provider');
    }

//    public function user($includeInActive = true): BelongsTo
//    {
//        $belongsTo = $this->belongsTo('\App\Models\User', 'user_id');
//        if ($includeInActive) {
//            $belongsTo->withoutGlobalScopes();
//        }
//        return $belongsTo;
//    }

    public function customer(): belongsTo
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function getNameRatingAttribute()
    {
        return $this->rating . ' - ' . $this->user->name;
    }
}
