<?php

namespace Snap\Taxonomy\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;

use Snap\Database\Model\Traits\IsRestorable;
use Snap\Database\Model\Traits\HasStatus;
use Snap\Decorator\Contracts\DecoratorInterface;
use Snap\Meta\Models\Traits\HasMeta;
use Snap\Meta\Relationships\MetaFields;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Vocabulary extends Model implements RestorableInterface
{
    use SoftDeletes;
    use HasSlug;
    //use MetaTrait;
    //use StatusTrait;
    //use PrecedenceTrait;
    //use HierarchicalTrait;
    use HasActive;
    //use HasMediaTrait; // Implement HasMedia if used
    use IsRestorable; // Implement RestorableInterface if used

    protected $fillable = [
        'name',
        'handle',
        'max_depth',
        'active',
    ];

    public static $rules = [
        'name' => 'required',
        'handle' => 'required',
    ];

    protected static $booleans = [
        'active',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    //protected $display_field = 'name';

    protected $table = 'snap_vocabularies';


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
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('handle');
    }

    public function getMetaFormAttribute()
    {
        //if (isset($this))
    }

    public function scopeLocate($query, $vocabulary)
    {
        if (is_int($vocabulary)) {
            return $query->where('id', '=', $vocabulary)->first();
        }


        return $query->where('handle', $vocabulary)->orWhere('name', $vocabulary);
    }

    //public function attributes(): MetaFields
    //{
    //    $fields = [];
    //    if ($this->exists) {
    //        foreach ($this->template->inputs() as $key => $field) {
    //            $fields[$key] = $field;
    //        }
    //
    //    }
    //    return $this->metaFields($fields);
    //}



}