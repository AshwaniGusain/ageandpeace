<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasCoordinates;
use Snap\Database\Model\Traits\HasPublishDate;
use Snap\Database\Model\Traits\IsRestorable;
use Snap\Database\Model\Traits\HasHierarchy;
use Snap\Database\Model\Traits\HasPrecedence;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\IsSearchable;
use Snap\Taxonomy\Traits\HasTaxonomy;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class KitchenSink extends Model implements RestorableInterface, HasMedia
{
    use HasSlug;
    use SoftDeletes;
    use HasPrecedence;
    use HasHierarchy; // parent_id
    use HasActive;
    use HasMediaTrait; // Implement HasMedia if used
    use IsRestorable; // Implement RestorableInterface if used
    use HasCoordinates;
    use HasTaxonomy;
    use HasPublishDate;
    use IsSearchable;

    protected $spatialFields = [
        'coordinates',
    ];

    protected $appends = ['lat', 'lng'];

    public static $rules = [
        'name' => 'required',
        'slug' => 'required',
    ];

    protected static $booleans = [
        'active'
    ];

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'date',
        'start_date',
        'end_date',
        'time',
        'timezone',
        'textarea',
        'wysiwyg',
        'float',
        'price',
        'number',
        'email',
        'url',
        'password',
        'select',
        'select2',
        'address',
        'city',
        'state',
        'zip',
        'coordinates',
        'phone',
        'radios',
        'color',
        'repeatable',
        'checkbox',
        'active',
        'status',
        'precedence',
        'dependent',
        'toggle',
        'keyvalue',
    ];

    protected $dates = ['deleted_at'];

    protected $sanitization = [
        'body' => 'clean_html',
    ];

    protected $casts = [
        'repeatable' => 'array',
    ];

    protected $table = 'kitchensink';
    protected $with =['tags'];

    //protected $displayNameField = 'title';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    //public function getRouteKeyName()
    //{
    //    // id is needed for the admin and slug for the frontend so we check the
    //    // request to see if the module parameter was injected on the request which
    //    // only occurs in the admin.
    //    $actions = request()->route()->getAction();
    //    if (isset($actions['module'])) {
    //        return 'id';
    //    }
    //
    //    return 'slug';
    //}

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

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'default');

        return $morphMany;
    }

    public function tags() : BelongsToMany
    {
        return $this->hasTaxonomy();
    }

    public function getPublicUrlAttribute()
    {
        return url('kitchensink/'.$this->slug);
    }

    /*public function registerMediaCollections()
    {
        $this->addMediaCollection('post-hero')->singleFile()->acceptsFile(function (File $file) {
            return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
        })->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
        })
        ;
        //add options

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo('\App\Models\Category', 'category_id');
    }

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Admin\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'post-hero');

        return $morphMany;
    }

    public function setPublishDateAttribute($date)
    {
        $ts = date('Y-m-d H:i:s', strtotime($date));
        $this->attributes['publish_date'] = ($ts) ? Carbon::parse($ts) : $date;

        return $this;
    }

    public function getDisplayImageAttribute()
    {
        return $this->image()->first();
    }*/

    //public function setRepeatableAttribute($value)
    //{
    //    dd($value);
    //}

}
