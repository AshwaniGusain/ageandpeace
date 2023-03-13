<?php

namespace Snap\Admin\Models;

use Snap\Database\Model\Traits\HasBooleanColumns;
use Snap\Database\Model\Traits\HasDisplayName;
use Snap\Database\Model\Traits\HasMagicIsAndHas;
use Snap\Database\Model\Traits\HasRelationships;
use Snap\Database\Model\Traits\HasSanitization;
use Snap\Database\Model\Traits\SoftDeleteHelper;
use Snap\Database\Model\Traits\HasUniqueColumns;
use Snap\Database\Model\Traits\HasValidation;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasMagicIsAndHas;
    use HasValidation;
    use HasSanitization;
    use HasRelationships;
    use HasUniqueColumns;
    use HasBooleanColumns;
    use SoftDeleteHelper;
    use HasDisplayName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

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
    public static $rules = [
        'name' => 'required',
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


}
