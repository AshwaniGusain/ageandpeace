<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\IsRestorable;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PostAuthor extends Model implements HasMedia, RestorableInterface
{
    use HasSlug;
    use HasMediaTrait;
    use SoftDeletes;
    use IsRestorable;
    use HasActive;

    // Not needed because it will automatically increment
    public static $rules = [
        'name'  => 'required',
        //'bio'        => 'required',
        'image' => 'mimes:jpeg,png',
    ];

    protected static $booleans = [
        'active',
    ];

    protected $fillable = [
        'name',
        'bio',
        'active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    protected $sanitization = [
        'bio' => 'clean_html',
    ];

    /********************************************************************************/
    /* MODEL SETUP METHODS  */
    /********************************************************************************/

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        // id is needed for the admin and slug for the frontend so we check the
        // request to see if the module parameter was injected on the request which .
        $actions = request()->route()->getAction();
        if (isset($actions['module'])) {
            return 'id';
        }

        return 'slug';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('post-authors')->singleFile()->acceptsFile(function (File $file) {
            return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
        })->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
        })
        ;
        //add options

    }


    /********************************************************************************/
    /* RELATIONSHIPS METHODS */
    /********************************************************************************/
    public function posts(): HasMany
    {
        return $this->hasMany('\App\Models\Post', 'author_id');
    }

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'post-authors');

        return $morphMany;
    }


    /********************************************************************************/
    /* SCOPES METHODS */
    /********************************************************************************/

    /********************************************************************************/
    /* ATTRIBUTE METHODS */
    /********************************************************************************/

}
