<?php

namespace Snap\Page\Models;

use Cache;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\DisplayImageInterface;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasHierarchy;
use Snap\Meta\Models\Contracts\MetaInterface;

use Snap\Database\Model\Traits\HasUserInfo;
use Snap\Database\Model\Traits\IsRestorable;
use Snap\Database\Model\Traits\HasStatus;
use Snap\Meta\Models\Traits\HasMeta;
use Snap\Meta\Relationships\MetaFields;
use Snap\Page\Thumb;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model implements RestorableInterface, MetaInterface, DisplayImageInterface
{
    use SoftDeletes;
    use HasSlug;
    use HasMeta;
    use HasStatus;
    use HasUserInfo;
    use HasHierarchy;
    use IsRestorable {
        getArchivableKeys as baseGetArchivableKeys;
        restoreVersion as baseRestoreVersion;
    }

    protected $fillable = [
        'uri',
        'name',
        'type',
        'publish_date',
        'status',
        'thumb',
        'meta',
    ];

    public static $rules = [
        //'name' => 'required',
        'uri' => 'required|unique:snap_pages,uri,{id}',
        'type' => 'required',
    ];

    protected static $booleans = [

    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    protected $table = 'snap_pages';

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_DRAFT = 'draft';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($page) {
            // We'll dynamically set the parent_id based on the URI so we can have one less
            // field a user will have to worry about filling out.
            $page
                ->determineName()
                ->determineParent()
                ->determineSlug()
                ->clearCache()
            ;

        });
    }

    public function determineName()
    {
        if (empty($this->name)) {
            $this->name = $this->uri;
        }

        return $this;
    }

    public function determineParent()
    {
        $segments = $this->segments();
        array_pop($segments);
        $parentUri = implode('/', $segments);

        if (!empty($parentUri)) {
            $parent = static::where('uri', $parentUri)->first();
            if ($parent) {
                $this->parent_id = $parent->id;
            }
        }

        return $this;
    }

    public function determineSlug()
    {
        $segments = $this->segments();
        $this->slug = array_pop($segments);

        return $this;
    }

    public function clearCache()
    {
        if (Cache::has('page_'.$this->uri)) {
            Cache::forget('page_'.$this->uri);
        }

        return $this;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        // id is needed for the admin and slug for the frontend so we check the
        // request to see if the module parameter was injected on the request which
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
                            ->generateSlugsFrom('name')
                            ->saveSlugsTo('slug')
                            ->doNotGenerateSlugsOnUpdate()
                            ;
    }

    public function meta(): MetaFields
    {
        $fields = [];
        if ($this->exists && $template = $this->template) {
            foreach ($template->inputs() as $key => $field) {
                $fields[$key] = $field;
            }
        }

        return $this->metaFields($fields);
    }

    public function getRawMetaAttribute()
    {
        return $this->meta()->getRaw();
    }

    public function getMetaAttribute()
    {
        return $this->meta()->get();
    }

    public function getTemplateAttribute()
    {
        return \Template::get($this->type);
    }

    public function getUrlAttribute()
    {
        return url($this->uri);
    }

    public function getThumbUrlAttribute()
    {
        //return url('storage/pages/'.$this->thumb_name.'.jpg');
        return $this->thumb->url();
    }

    public function getDisplayImageAttribute()
    {
        return $this->thumb;
    }

    public function getThumbAttribute()
    {
        return new Thumb($this, config('snap.pages.thumbnail'));
    }

    public function getThumbNameAttribute()
    {
        //return pathinfo($this->attributes['thumbnail'], PATHINFO_FILENAME) ?? md5($this->uri);
        return md5($this->uri);
    }

    public function getThumbNameBase64Attribute()
    {
        return 'data:image/jpg;base64,'.base64_encode(file_get_contents($this->thumb->path()));
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function render()
    {
        //$data = array_merge(['page' => $this], (array) $this->meta()->get(), $data);

        $data = array_merge(['page' => $this], (array) $this->meta()->get());

        return $this->template->with($data)->render();
    }

    public function ui($data = [])
    {
        $data = array_merge(['page' => $this], (array) $this->meta()->get(), $data);

        return $this->template->with($data)->ui();
    }

    public function hasUriParams()
    {
        return !empty($this->numUriParams());
    }

    public function getNumUriParamsAttribute()
    {
        return $this->template->numUriParams;
    }

    public function uriParams()
    {
        return array_diff($this->segments(), \Request::segments());
    }

    public function segments()
    {
        return explode('/', trim($this->uri, '/'));
    }

    public static function hasThumbnails()
    {
        return config('snap.pages.thumbnail.enabled');
    }

    public function isPublished()
    {
        return $this->status == static::STATUS_PUBLISHED;
    }

    public function isDraft()
    {
        return $this->status == static::STATUS_DRAFT;
    }

    public function setMetaAttribute($data)
    {
        $this->meta()->set($data);

        return $this;
    }

    public function scopeOnlyPublished($query)
    {
        return $query->where('status', '=', static::STATUS_PUBLISHED);
    }

    public function scopeWithStatuses($query, $statuses = [])
    {
        foreach ($statuses as $status) {
            $query->where('status', '=', $status);
        }
        return $query;
    }

    /**
     * Overwritten method from IsRestorable trait to get all the keys in
     * which to archive in particular the meta information.
     *
     * @return array
     */
    public function getArchivableKeys()
    {
        $keys = $this->baseGetArchivableKeys();
        $keys[] = 'meta';
        return $keys;
    }

    ///**
    // * Overwritten method from IsRestorable trait to properly set the data
    // * on the model in particular the meta information.
    // *
    // * @return boolean
    // */
    public function restoreVersion($version = null)
    {
        $restored = $this->baseRestoreVersion();
        $data = $this->getRestoreData($version);
        $metaRestored = $this->meta()->set($data['meta'])->save();
        return $restored && $metaRestored;
    }
}
