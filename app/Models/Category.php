<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\HasHierarchy;
use Snap\Database\Model\Traits\HasPrecedence;
use Snap\Database\Model\Traits\ShouldClearCache;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Watson\Rememberable\Rememberable;

class Category extends Model implements HasMedia
{
    //use HasPrecedence;
    use HasHierarchy;
    use HasMediaTrait;
    use HasSlug;
    use SoftDeletes;
    use HasActive;
    use Rememberable;
    use ShouldClearCache;

    const PRECEDENCE_COLUMN = 'categories.precedence';

    public static $booleans = ['active'];

    // Not needed because it will automatically increment
    //protected static $unique = ['slug'];

    public static $rules = [
        'name'        => 'required',
        'slug'        => 'required',
        'image'       => 'mimes:jpeg,png',
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'slug',
        'description',
        'active',
        'precedence',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'parent',
        'parentCategory',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $unique = [
        'slug',
    ];


    protected $appends = [
        'url',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if (is_null($category->precedence)) {
                $category->precedence = 0;
            }
        });
        static::deleting(function ($category) {
            foreach ($category->childrenCategories as $childCategory) {
                $childCategory->delete();
            }
//            foreach ($category->tasks as $task) {
//                $task->category->dissociate();
//                $task->save();
//            }
            // todo add customer task back in
//            foreach ($category->customerTasks as $customerTask) {
//                $customerTask->category_id = null;
//                $customerTask->save();
//            }
            foreach ($category->providers as $provider) {
                $provider->categories()->detach($category->id);
            }

            foreach ($category->posts as $post) {
                $post->category()->dissociate();
                $post->save();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    // COMMENTED OUT BECAUSE IT CAUSES ISSUES IN THE ADMIN WHICH IS EXPECTING AN ID!!!
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

    public function tasks()
    {
        return $this->hasMany('\App\Models\Task');
    }

    public function posts()
    {
        return $this->hasMany('\App\Models\Post');
    }

    public function childPosts()
    {
        $posts = [];
        $childIds = $this->childrenCategories->pluck('id');

        foreach (Post::whereIn('category_id', $childIds)->get() as $post) {
            $posts[] = $post;
        }
        return collect($posts);
    }


    public function customerTasks()
    {
        return $this->hasMany('\App\Models\CustomerTask');
    }

    public function providerTypes()
    {
        return $this->belongsToMany('\App\Models\ProviderType')->withTimestamps()->withPivot('precedence');
    }

    public function parentCategory()
    {
        return $this->belongsTo('\App\Models\Category', 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany('\App\Models\Category', 'parent_id');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('categories.parent_id');
    }

    public function scopeSubcategories($query)
    {
        return $query->whereNotNull('categories.parent_id');
    }

    public function highestParent()
    {
        if ($this->parentCategory) {
            return $this->parentCategory->highestParent();
        }

        return $this;
    }

    public function allChildren()
    {
        $children = collect([$this]);

        if ($this->childrenCategories) {
            $children = $children->merge($this->childrenCategories);

            foreach ($this->childrenCategories as $child) {
                $children = $children->merge($child->childrenCategories);
            }
        }

        return $children->pluck('id');
    }

    public function allBottomLevelCategories()
    {
        $children = collect([]);

        if ($this->childrenCategories) {

            if ($this->parentCategory()->first()){
                $children = $children->merge($this->childrenCategories);
            } else {
                foreach ($this->childrenCategories as $child) {
                    $children = $children->merge($child->childrenCategories);
                }
            }
        }

        return $children->pluck('id');
    }


    public function isRoot()
    {
        return empty($this->parent_id);
    }

    public function isChild()
    {
        return !empty($this->parent_id);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('category-hero')
             ->useDisk('categories')
             ->singleFile()
             ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
        })->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
            $this->addMediaConversion('lg')->fit(Manipulations::FIT_CROP, 1200, 500)->nonQueued();
        })
        ;

        $this->addMediaCollection('category-default')
             ->useDisk('categories')
             ->singleFile()
             ->acceptsFile(function (File $file) {
                 return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
         })->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
        })
        ;
    }

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'category-hero');

        return $morphMany;
    }

    public function getImgUrlAttribute()
    {
        $image = $this->image()->first();

        if ($image) {
            return $image->getUrl();
        }
        return null;
    }

    public function defaultProviderImage(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'category-default');

        return $morphMany;
    }

    public function getDefaultProviderImgUrlAttribute()
    {
        $image = $this->defaultProviderImage()->first();

        if ($image) {
            return $image->getUrl();
        }
        return null;
    }

    public function getUrlAttribute()
    {
        if ($this->isChild()) {
            return route('subcategory', ['category' => $this->slug]);
        } else {
            return route('providers', ['category' => $this->id]);
        }
    }

}
