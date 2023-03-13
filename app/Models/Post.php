<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Snap\Admin\Models\Contracts\DisplayImageInterface;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasPublishDate;
use Snap\Database\Model\Traits\HasPublished;
use Snap\Database\Model\Traits\IsRestorable;
use Snap\Database\Model\Traits\ShouldClearCache;
use Snap\Support\Helpers\TextHelper;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Watson\Rememberable\Rememberable;

class Post extends Model implements HasMedia, RestorableInterface, DisplayImageInterface
{
    use HasSlug;
    use HasMediaTrait;
    use SoftDeletes;
    use HasPublishDate;
    use HasPublished;
    use IsRestorable;
    use Rememberable;
    use ShouldClearCache;

    // Not needed because it will automatically increment
    //protected static $unique = ['slug'];
    public static $rules = [
        'title'       => 'required',
        //'category_id' => 'required',
        //'slug'        => 'required',
        'body'        => 'required',
        'image'       => 'mimes:jpeg,png',
    ];

    protected static $booleans = [
        'featured',
    ];

    protected $fillable = [
        'title',
        'category_id',
        'slug',
        'body',
        'excerpt',
        'status',
        'featured',
        'publish_date',
        'precedence',
        'author_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    protected $sanitization = [
        'excerpt' => 'clean_html',
        'body' => 'clean_html',
    ];

    protected $with = [
        //'category',
        //'author',
        //'image'
    ];

    protected $appends =['url'];

    const PUBLISHED_COLUMN = 'status';
    const PUBLISHED_VALUE = 'published';

    /********************************************************************************/
    /* MODEL SETUP METHODS  */
    /********************************************************************************/
    protected static function boot()
    {
        parent::boot();
    }

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
        return SlugOptions::create()
                          ->generateSlugsFrom('title')
                          ->saveSlugsTo('slug')
                          //->doNotGenerateSlugsOnUpdate()
                ;
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('post-hero')
             ->useDisk('posts')
             ->singleFile()
             ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
        })
        ->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
            $this->addMediaConversion('sm')->fit(Manipulations::FIT_CROP, 360, 200)->nonQueued();
            $this->addMediaConversion('md')->fit(Manipulations::FIT_CROP, 680, 400)->nonQueued();
            $this->addMediaConversion('lg')->fit(Manipulations::FIT_CROP, 1200, 500)->nonQueued();
        })
        ;
        //add options

    }


    /********************************************************************************/
    /* RELATIONSHIPS METHODS */
    /********************************************************************************/
    public function category(): BelongsTo
    {
        return $this->belongsTo('\App\Models\Category', 'category_id');
    }

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'post-hero');

        return $morphMany;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo('\App\Models\PostAuthor', 'author_id');
    }


    /********************************************************************************/
    /* SCOPES METHODS */
    /********************************************************************************/
    public function scopeCategories($query)
    {
        // Get the Category IDs of only those associated with posts.
        $categoryIds = $query->whereNotNull('category_id')->pluck('category_id')->toArray();

        return Category::whereIn('id', $categoryIds);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }

    public function scopeNotFeatured($query)
    {
        return $query->where('featured', 0);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }


    /********************************************************************************/
    /* ATTRIBUTE METHODS */
    /********************************************************************************/
    public function relatedPostsAttribute($limit = null)
    {
        $query = $this
            ->where('id', '!=', $this->id)
            ->where('category_id', '=', $this->category_id)
            ->orderBy('publish_date', 'desc')
            ;
        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    //public function setPublishDateAttribute($date)
    //{
    //    $ts = date('Y-m-d H:i:s', strtotime($date));
    //    $this->attributes['publish_date'] = ($ts) ? Carbon::parse($ts) : $date;
    //
    //    return $this;
    //}

    public function getDisplayImageAttribute()
    {
        return $this->cached_image->first();
    }

    public function getCachedImageAttribute()
    {
        $cacheKey = $this->id.'-post-image'.strtotime($this->updated_at);
        if (\Cache::has($cacheKey)) {
            return \Cache::get($cacheKey);
        }
        $image = $this->image()->first();
        if ($image) {
            \Cache::forever($cacheKey, $image);
        }

        return $image;
        //if (!isset($this->attributes['image'])) {
        //    $this->attributes['image'] = $this->image()->first();
        //}
        //return $this->attributes['image'];
    }

    public function getExcerptAttribute($str, $limit = 200)
    {
        $excerpt = empty($this->attributes['excerpt']) ? strip_tags($this->attributes['body']) : $this->attributes['excerpt'];
        $excerpt = TextHelper::ellipsize($excerpt, $limit ? : 200);

        return $excerpt;
    }

    public function getAuthorNameAttribute()
    {
        if ($this->author) {
            return $this->author->name;
        }

        return '';
    }

    public function getUrlAttribute()
    {
        return route('news.detail', ['post' => $this->slug]);
    }

    public function getLgImageUrlAttribute()
    {
        $image = $this->cached_image;
        if ($image) {
            return $image->getUrl('lg');
        }
        return $this->default_image;
    }

    public function getMdImageUrlAttribute()
    {
        $image = $this->cached_image;
        if ($image) {
            return $image->getUrl('md');
        }
        return $this->default_image;
    }

    public function getSmImageUrlAttribute()
    {
        $image = $this->cached_image;
        if ($image) {
            return $image->getUrl('sm');
        }
        return $this->default_image;
    }

    public function hasImage()
    {
        return $this->cached_image ? true : false;
    }

    public function getDefaultImageAttribute()
    {
        return asset('assets/images/logo_mobile.png');
    }

    public function getImageAltAttribute()
    {
        if ($this->hasImage()) {
            return $this->cached_image->meta()->get('alt');
        }

        return $this->title;
    }

}
