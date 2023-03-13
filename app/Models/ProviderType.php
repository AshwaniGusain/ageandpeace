<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Snap\Admin\Modules\ModuleModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\HasPrecedence;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProviderType extends Model
{
    //use HasPrecedence;
    use SoftDeletes;
    use HasSlug;
    use HasActive;

    public static $rules = [
        'name'      => 'required|string',
    ];

    public static $unique = [
        'slug',
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'vendor_type_group_id',
        'precedence',
        'active',
    ];

    protected static $booleans = [
        'active'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug')
                          ->doNotGenerateSlugsOnUpdate()
            ;
    }

    //public function category(): BelongsToMany
    //{
    //    return $this->belongsTo('\App\Models\Category');
    //}

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany('\App\Models\Category')->withTimestamps();
    }

    public function providerTypeGroup(): BelongsTo
    {
        return $this->belongsTo('\App\Models\ProviderTypeGroup');
    }

    public function providers(): HasMany
    {
        return $this->hasMany('\App\Models\Provider');
    }


}
