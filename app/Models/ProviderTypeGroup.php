<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Snap\Admin\Modules\ModuleModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Database\Model\Traits\HasActive;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProviderTypeGroup extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasActive;

    public static $rules = [
        'name'      => 'required|string',
    ];

    protected $dates = ['deleted_at'];

    //
    protected $fillable = [
        'name',
        'slug',
        'precedence',
        'active',
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

    public function providerTypes(): HasMany
    {
        return $this->hasMany('\App\Models\ProviderType');
    }
}
