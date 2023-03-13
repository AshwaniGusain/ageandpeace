<?php

namespace Snap\Taxonomy\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Meta\Models\Contracts\MetaInterface;
use Snap\Database\Model\Traits\IsRestorable;
use Snap\Meta\Models\Traits\HasMeta;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Term extends Model implements RestorableInterface
{
    use SoftDeletes;
    use HasSlug;
    //use StatusTrait;
    //use PrecedenceTrait;
    //use HierarchicalTrait;
    //use ActiveTrait;
    //use HasMediaTrait; // Implement HasMedia if used
    use IsRestorable; // Implement RestorableInterface if used

    protected $fillable = [
        'name',
        'slug',
        'active',
    ];

    public static $rules = [
        'name' => 'required|unique:snap_terms,name,{id}',
        //'slug' => 'unique:snap_terms,slug,{slug}', // Doesn't need to be required because it will automatically be created
    ];

    protected static $booleans = [

    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    //protected $display_field = 'name';

    protected $table = 'snap_terms';


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
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function scopeLocate($query, $term)
    {
        if (is_int($term)) {
            return $query->where('id', '=', $term)->first();
        }

        return $query->where('name', '=', $term)->orWhere('slug', '=', $term)->first();
    }

}