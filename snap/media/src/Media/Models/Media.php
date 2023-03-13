<?php

namespace Snap\Media\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;
//use Snap\Admin\Modules\ModuleModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Snap\Admin\Models\Contracts\DisplayImageInterface;
use Snap\Database\Model\Traits\HasDisplayName;
use Snap\Database\Model\Traits\HasValidation;
use Snap\Database\Model\Traits\SoftDeleteHelper;
use Snap\Meta\Models\Traits\HasMeta;
use Snap\Meta\Relationships\MetaFields;
use Snap\Taxonomy\Traits\HasTaxonomy;
use Spatie\MediaLibrary\MediaObserver;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Snap\Database\Model\Traits\HasRelationships;

class Media extends SpatieMedia implements DisplayImageInterface
{
    use HasDisplayName;
    use HasRelationships;
    use HasValidation;
    use HasMeta;
    use HasTaxonomy;
    use SoftDeleteHelper;

    protected $table = 'snap_media';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'size',
        'order_column',
    ];

    protected $displayNameField = 'file_name';

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        //'model_type' => 'required',
        //'model_id' => 'required',
    ];

    /**
     * Custom messages.
     *
     * @var array
     */
    protected static $messages = [];

    /**
     * Error message bag.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validator instance
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Determines whether to run validation on save.
     *
     * @var boolean
     */
    protected $skipValidation = false;

    /**
     * Determines whether to auto validate fields on save.
     *
     * @var boolean
     */
    protected $autoValidate = true;

    public static function boot()
    {
        parent::bootTraits();
        static::observe(new MediaObserver());

        //static::saving(function ($page) {
        //    if (empty($page->model_type)) {
        //        $page->model_type = DefaultMedia::class;
        //    }
        //});
    }

    public function getDisplayImageAttribute()
    {
        return $this;
    }

    public function tags() : BelongsToMany
    {
        return $this->hasTaxonomy();
    }

    public function meta() : MetaFields
    {
        return $this->metaFields([]);
    }

    public function metaForm()
    {
        return \Taxonomy::vocabularyMetaForm($this->collection_name);
    }
}
