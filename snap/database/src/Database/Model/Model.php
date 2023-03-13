<?php

namespace Snap\Database\Model;

use Snap\Database\Model\Traits\HasMagicIsAndHas;
use Snap\Database\Model\Traits\SoftDeleteHelper;
use Snap\Database\Model\Traits\HasValidation;
use Snap\Database\Model\Traits\HasSanitization;
use Snap\Database\Model\Traits\HasRelationships;
use Snap\Database\Model\Traits\HasUniqueColumns;
use Snap\Database\Model\Traits\HasBooleanColumns;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use HasMagicIsAndHas;
    use HasValidation;
    use HasSanitization;
    use HasRelationships;
    use HasUniqueColumns;
    use HasBooleanColumns;
    use SoftDeleteHelper;

    /**
     * Sets which events can be observed by the model.
     *
     * @param  string  $table
     * @return void
     */

    protected $observables = ['validating', 'validated', 'sanitizing', 'sanitized'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected static $rules = [];

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

    /**
     * Colunmns that should be considered unique (can also be a nested array for compound keys).
     *
     * @var array
     */
    protected static $unique = [];

    /**
     * Sanitization function to call on save.
     *
     * @var array
     */
    protected $sanitization = [];

    /**
     * Colunmns that should be considered booleans and help with the MagicIsHas trait.
     *
     * @var array
     */
    protected static $booleans = [];

    const SHARED_RELATIONSHIPS_TABLE = 'snap_relationships';

    /**
     * User exposed observable events
     *
     * @var array
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        // Set the table based on the config mapping if it exists
        $this->setTable($this->table);
    }

    /**
     * Overwrites parent model class's setTable method to first check the Config object for a table name's alias.
     *
     * @param  string  $table
     * @return void
     */
    public function setTable($table)
    {
        $this->table = config('snap.tables.'.$table, $table);
    }

    /**
     * Adds mutator dates to the model's $dates property.
     *
     * @param $dates
     * @return $this
     */
    public function addDates($dates)
    {
        $dates = is_array($dates) ? $dates : func_get_args();

        $this->dates = array_merge($this->dates, $dates);

        return $this;
    }

}
