<?php

namespace Snap\Taxonomy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Meta\Models\Traits\HasMeta;
use Snap\Meta\Relationships\MetaFields;
use Snap\Database\Model\Traits\HasMagicIsAndHas;
class Taxonomy extends Model
{
    use SoftDeletes;
    use HasMeta;
    use HasMagicIsAndHas {
        __get as ___get;
    }

    protected $fillable = [
        'vocabulary_id',
        'term_id',
        'parent_id',
        'context',
    ];

    public static $rules = [
        'vocabulary_id' => 'required',
        'term_id' => 'required',
    ];

    protected static $booleans = [

    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    protected $with = ['vocabulary', 'term'];

    protected $table = 'snap_taxonomies';

    protected $displayNameField = 'name';

    protected $appends = ['name', 'slug'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            $model->depth = static::determineDepth($model);
        });
    }

    public function getVocabularyAndNameAttribute()
    {
        return $this->vocabulary->name . ': ' . $this->term->name;
    }

    public function getDisplayNameAttribute()
    {
        if (empty($this->displayNameField)) {
            return $this->getVocabularyAndNameAttribute();
        }

        return $this->{$this->displayNameField};
    }

    public function setDisplayNameAttribute($displayName)
    {
        $this->displayNameField = $displayName;

        return $this;
    }

    /**
     * Get the term this taxonomy belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vocabulary() : BelongsTo
    {
        return $this->belongsTo(Vocabulary::class);
    }

    /**
     * Get the term this taxonomy belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term() : BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the parent taxonomy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'parent_id');
    }
    /**
     * Get the children taxonomies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Taxonomy::class, 'parent_id');
    }

    public function getNameAttribute()
    {
        if ($this->term) {
            return $this->term->name;
        }
    }

    public function getSlugAttribute()
    {
        if ($this->term) {
            return $this->term->slug;
        }
    }

    public static function determineDepth($model)
    {
        if (empty($model->parent_id)) {
            return 0;
        }
        $depth = 0;
        while($model && $model->parent_id) {
            $model = static::where('id', $model->parent_id)->first();
            $depth++;
        }

        return $depth;
    }

    public function meta() : MetaFields
    {
        return $this->metaFields([]);
    }

    public function metaForm()
    {
        return \Taxonomy::vocabularyMetaForm($this->handle);
    }

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
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        //return $this->where($this->getRouteKeyName(), $value)->first();
        return $this->findTerm($value);
    }

    public function scopeFindTerm($query, $term, $vocabulary_id = null)
    {
        $query->whereHas('term', function ($query) use ($term) {
            $query->where('slug', '=', $term);
        });

        if ($vocabulary_id) {
            $query->whereHas('vocabulary', function ($query) use ($vocabulary_id) {
                $query->where('id', '=', $vocabulary_id);
            });
        }

        $term = $query->first();

        return $term;
    }

    public function scopeSaveTerm($query, $term, $vocabulary = null, $parent = null)
    {
        $taxonomy = new static;

        if (!$term instanceof Term) {
            $term = Term::locate($term);
            if (!$term) {
                $term = Term::Create(['name' => $term]);
            }
        }

        if (empty($vocabulary)) {
            $vocabulary = $this->getDefaultVocabularyAttribute();
        } else {
            $vocabulary = Vocabulary::locate($vocabulary);
        }

        if (!$parent instanceof Term) {
            $parent = Term::locate($parent);
        }

        $taxonomy->term_id = $term->id;
        $taxonomy->vocabulary_id = $vocabulary->id;
        $taxonomy->parent_id = $parent->id;
        $taxonomy->save();

        return $taxonomy;
    }

    public function getDefaultVocabularyAttribute()
    {
        $default = config('snap.taxonomy.default');
        $vocabulary = Vocabulary::locate($vocabulary);

        return $vocabulary;
    }


    public function related($model)
    {
        return $this->belongsToMany($model);
    }

    public function __get($key)
    {
        $attribute =  $this->___get($key);

        if (is_null($attribute)) {

            foreach (\Admin::models() as $handle => $model) {
                if ($key == Str::plural($handle)) {
                    return $this->sharedForeignRelationship($model);
                }
            }
        }

        return $attribute;
    }

}