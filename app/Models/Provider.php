<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\DisplayImageInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\DBUtil;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\HasCoordinates;
use Snap\Database\Model\Traits\IsSearchable;
use Snap\Database\Model\Traits\ShouldClearCache;
use Snap\Support\Helpers\TextHelper;
use Snap\Support\Helpers\UrlHelper;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Provider extends Model implements HasMedia, DisplayImageInterface
{
    use HasMediaTrait;
    use SoftDeletes;
    use HasSlug;
    use IsSearchable;
    use HasCoordinates;
    use ShouldClearCache;
    //use HasActive;

    //const ACTIVE_COLUMN = 'users.active';
    const COORDINATES = 'geo_point';

    public static $rules = [
        //'user_id'     => 'required',
        'street'      => 'required',
        'city'        => 'required',
        'state'       => 'required',
        'zip'         => 'required',
        //'phone'       => 'required',
        //'website'     => 'required',
        //'description' => 'required',
        'logo'        => 'mimes:jpeg,png',
        'image'        => 'mimes:jpeg,png',
        //'slug'        => 'required',
    ];

    protected static $booleans = [
        'national',
        'active',
    ];

    protected $with = [
        'user',
        'company',
    ];

    protected $fillable = [
        'user_id',
        'slug',
        'company_id',
        'street',
        'city',
        'state',
        'zip',
        'website',
        'phone',
        'description',
        'expiration_date',
        'membership_type_id',
        'national',
        'provider_type_id',
        'active',
    ];

    protected $spatialFields = [
        'geo_point',
    ];

    protected $searchFields = [
        'user:name',
        'company:name',
        'city',
        'state',
        'zip'
    ];

    protected $dates = ['deleted_at', 'expiration_date'];

    protected $appends = [
        'logo_url',
        'image_url',
        'latitude',
        'longitude',
        'display_image',
        'excerpt',
        'rating',
        'url',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $unique = [
        'slug',
    ];
    //protected $dateFormat = 'm/d/Y';

    protected static function boot()
    {
        // To prevent errors when seeding
        if (defined('SEEDING') && SEEDING === true) {
            static::$unique = [];
        }

        parent::boot();
        static::saved(function ($provider) {
            if (!empty($provider->zip)) {
                $zip = Zip::where('zipcode', $provider->zip)->first();
                if ($zip) {
                    $provider->zipCodes()->syncWithoutDetaching([$zip->id]);
                }
            }
        });

        static::deleting(function ($provider) {
            $provider->user->delete();
            if ($provider->membership) {
                $provider->membership->delete();
            }
            if ($provider->ratings) {
                foreach ($provider->ratings as $rating) {
                    $rating->delete();
                }
            }

            $provider->zipCodes()->detach();
        });
    }

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

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function user($includeInActive = true): BelongsTo
    {
        $belongsTo = $this->belongsTo('\App\Models\User', 'user_id');
        if ($includeInActive) {
            $belongsTo->withoutGlobalScopes();
        }

        return $belongsTo;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo('\App\Models\Company', 'company_id');
    }
    //
    //public function categories(): BelongsToMany
    //{
    //    return $this->belongsToMany('\App\Models\Category');
    //}

    public function zipCodes()
    {
        return $this->belongsToMany('\App\Models\Zip')->withTimestamps();
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo('\App\Models\MembershipType');
    }

    public function ratings()
    {
        return $this->hasMany('\App\Models\Rating', 'provider_id');
    }

    public function providerType(): BelongsTo
    {
        return $this->belongsTo('\App\Models\ProviderType');
    }

    public function scopeActive($query)
    {
        if (DBUtil::joinExists($query, 'users')) {
            return $query->where('users.active', 1);
        }
        return $query->whereHas('user', function ($q) {
            $q->where('active', 1);
        });
    }

    public function scopeInactive($query)
    {
        if (DBUtil::joinExists($query, 'users')) {
            return $query->where('users.active', 0);
        }

        return $query->whereHas('user', function ($q) {
            $q->where('active', 0);
        });
    }

    // Gets all providers near a customer user 8046 = 5 miles, 16093 = 10, 32186 = 20 (meters)
    //https://gis.stackexchange.com/questions/140139/count-all-records-x-distance-from-a-given-point-using-sql-in-cartodb
    public function scopeZipRadius($query, $user_geo, $radius = 32186)
    {
        $zips = Zip::distanceSphere('geo_point', $user_geo, $radius)->pluck('id');

        $zips = $query->whereHas('zipCodes',
            function ($query) use ($zips) {
                return $query->whereIn('zip_id', $zips);
            })->orWhere('national', 1);

        //dd($zips);
        return $zips;
        //return $this->scopeDistanceSphere($query, 'geo_point', $user_geo, $radius)->orWhereHas('zipCodes',
        //    function ($query) use ($zips) {
        //        return $query->whereIn('zip_id', $zips);
        //    })->orWhere('national', 1);
    }

    public function scopeWithCategory($query, $category)
    {
        if (is_int($category)) {
            $category = Category::find($category);
        } else if (is_string($category)) {
            $category = Category::where('slug', $category)->first();
        }

        $query->whereHas('providerType', function ($q1) use ($category) {
            $q1->whereHas('categories', function ($q2) use ($category) {
                $q2->where('category_id', $category->id);
                return $q2->orWhereIn('category_id', $category->childrenCategories->pluck('id'));
            });
        });

        return $query;

    }

    // Gets all providers who are grandchildren of a specific category
    //public function scopeMasterCategory($query, $category)
    //{
    //    if (is_int($category)) {
    //        $category = Category::find($category);
    //    }
    //
    //    return $query->whereHas('categories', function ($query) use ($category) {
    //        $query->where('category_id', $category->id);
    //        return $query->orWhereIn('category_id', $category->childrenCategories->pluck('id'));
    //    });
    //    /*return $query->whereHas('categories', function ($query) use ($category) {
    //        return $query->whereIn('category_id', $category->allBottomLevelCategories());
    //    });*/
    //}
    //
    //// Gets all providers of a specific sub category
    //public function scopeSubCategory($query, $subCategory)
    //{
    //    if (is_int($subCategory)) {
    //        $subCategory = Category::find($subCategory);
    //    }
    //
    //    return $query->whereHas('categories', function ($query) use ($subCategory) {
    //        return $query->where('category_id', $subCategory->id);
    //    });
    //}

    // Gets all providers of a specific provider category in a set radius from the user
    public function scopeProviderCategory($query, $providerCategory)
    {
        return $query->whereHas('categories', function ($query) use ($providerCategory) {
            return $query->where('category_id', $providerCategory->id);
        });
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('provider-logo')
             ->useDisk('providers')
             ->singleFile()
             ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
            });

        $this->addMediaCollection('provider-hero')
             ->useDisk('providers')
             ->singleFile()
             ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/png';
            })->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')->width(100)->height(100)->nonQueued();
            });
    }

    public function logo(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', 'provider-logo');

        return $morphMany;
    }

    public function image(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', 'provider-hero');

        return $morphMany;
    }

    public function hasImage()
    {
        return $this->image()->first() ? true : false;
    }

    public function hasLogo()
    {
        return $this->logo()->first();
    }

    public function getDisplayImageAttribute()
    {
        if ($this->hasImage()) {
            return $this->image_url;
        } elseif ($this->hasLogo()) {
            return $this->logo_url;
        }

        return $this->default_image_url;
    }

    public function getLogoUrlAttribute()
    {
        if (count($this->logo) > 0) {
            return $this->logo[0]->getUrl();
        }

        return false;
    }

    public function getImageUrlAttribute()
    {
        if (count($this->image) > 0) {
            return $this->image[0]->getUrl();
        }

        return false;
    }

    public function getDefaultImageUrlAttribute()
    {
        if ($this->providerType) {
            $categories = $this->providerType->categories;
            if (!empty($categories[0])) {
                return $categories[0]->default_provider_img_url;
            }
        }
        //return asset('assets/images/logo_mobile.png');
    }

    public function getHeroImageUrlAttribute()
    {
        if ($this->hasImage()) {
            return $this->image_url;
        }

        return $this->default_image_url;
    }

    //public function setExpirationDateAttribute($date)
    //{
    //    $ts = date('Y-m-d H:i:s', strtotime($date));
    //    $this->attributes['expiration_date'] = ($ts) ? Carbon::parse($ts) : $date;
    //
    //    return $this;
    //}

    //public function getDisplayNameAttribute()
    //{
    //    $user = $this->user;
    //
    //    if ($user) {
    //        return $this->user->name;
    //    }
    //
    //    return null;
    //}

    public function getFullAddressAttribute()
    {
        return $this->street . ' ' . $this->city . ', ' . $this->state . ' ' . $this->zip;
    }

    public function getFullAddressFields()
    {
        return ['street', 'city', 'state', 'zip'];
    }

    public function hasAddressChanged()
    {
        $fields = $this->getFullAddressFields();
        foreach ($fields as $key) {
            if ( ! $this->originalIsEquivalent($key, $this->{$key})) {
                return true;
            }
        }

        return false;
    }

    public function getRatingAttribute()
    {
        $ratings = Rating::where('provider_id', $this->id)->pluck('rating');

        if ($ratings->count() < 1) {
            return false;
        }

        return round($ratings->avg() * 2) / 2;
    }

    public function getLatitudeAttribute()
    {
        if ($this->geo_point) {
            return $this->geo_point->getLat();
        }

        return null;
    }

    public function getLongitudeAttribute()
    {
        if ($this->geo_point) {
            return $this->geo_point->getLng();
        }

        return null;
    }

    public function getExcerptAttribute($str, $limit = 200)
    {
        $excerpt = strip_tags($this->description);
        $excerpt = TextHelper::ellipsize($excerpt, $limit ? : 200);

        return $excerpt;
    }

    public function getWebsiteAttribute()
    {
        return UrlHelper::prep($this->attributes['website']);
    }

    public function getPrettyWebsiteAttribute()
    {
        $website = trim(UrlHelper::unprep($this->website), '/');

        return explode('/', $website)[0];
    }

    public function getFormattedPhoneAttribute()
    {
        $number = preg_replace("#[^0-9]#", '', $this->phone);
        // https://gist.github.com/jefferyrdavis/5992271
        if(ctype_digit($number) && strlen($number) == 10) {
            $number = substr($number, 0, 3) .'-'. substr($number, 3, 3) .'-'. substr($number, 6);
        } else {
            if(ctype_digit($number) && strlen($number) == 7) {
                $number = substr($number, 0, 3) .'-'. substr($number, 3, 4);
            }
        }
        return $number;
    }

    public function getUrlAttribute()
    {
        return route('provider.detail', ['provider' => $this->slug]);
    }

    public function toArray()
    {
        $array = parent::toArray();
        if ( ! empty($array)) {
            //$array['name']  = $array['user']['name'];
            //$array['email'] = $array['user']['email'];
            //$array['active'] = $array['user']['active'];
            unset($array['user']);
        }

        return $array;
    }

}
