<?php

namespace Snap\Database\Model\Traits;

use Event;
use Snap\Database\DBUtil;
use Snap\Support\Helpers\DateHelper;

trait HasAutoValidation {

    /**
     * Boot the trait. Adds an observer class for validating.
     *
     * @return void
     */
    public static function bootHasAutoValidation()
    {
        Event::listen('eloquent.validating: '.static::class, function($model, $args) {
        //Event::listen('eloquent.validating:*', function($name, $args) {
        //    $model = $args[0];

            if ($model->canAutoValidate()) {
                $model->autoValidate();
            }
        });
    }

    /**
     * Determines whether auto validation can happen on saving with the model.
     *
     * @access  protected
     * @return  boolean
     */ 
    public function canAutoValidate()
    {
        return (isset($this->autoValidate) && $this->autoValidate === true);
    }

    /**
     * Automatically validate all the data for columns before saving based on the table meta info.
     *
     * @access  protected
     * @return  void
     */ 
    public function autoValidate()
    {
        if ($this->canAutoValidate()) {
            $columns = DBUtil::columnList($this->getTable());
            foreach($columns as $column) {
                $this->autoValidateAttribute($column);  
            }
        }
    }

    /**
     * Determines whether auto validation can happen on saving with the model.
     *
     * @access  protected
     * @return  array
     */ 
    public function autoValidateFields()
    {
        return (isset($this->autoValidateAttributes)) ? $this->autoValidateAttributes : [];
    }

    /**
     * Automatically validate a single column's data before saving based on the table meta info.
     *
     * @access  protected
     * @param   string  field name
     * @return void
     */
    public function autoValidateAttribute($column)
    {
        // Automatically return true if there is no value specified for the field.
        if ( ! array_key_exists($column, $this->attributes)) {
            return;
        }

        $value = $this->getAttribute($column);

        if (is_object($value) && method_exists($value, '__toString')) {
            $value = $value->__toString();
        }

        if (!empty($value)) {

            $columnInfo = DBUtil::columnInfo($this->getTable(), $column);

            $type = $columnInfo['type'];

            if ( ! is_array($value)) {

                $fieldName = "'".(str_replace('_', ' ', $column))."'";

                // Test for the length value of the column if specified.
                if ( ! empty($columnInfo['length'])) {
                    $this->addRule($column, 'max:'.$columnInfo['length'], $this->getValidationMessage($column.'.size', trans('db::validation.invalid_length')));
                }

                // Add additional tests for common types such as validating dates.
                switch($type) {
                    case 'enum':
                        $this->addRule($column, 'in:'.implode(',', $columnInfo['options']), $this->getValidationMessage($column.'.in', trans('db::validation.invalid_value')));
                        break;
                    // Test that the value is actually a valid date format.
                    case 'date': case 'datetime': case 'timestamp':
                        if (! DateHelper::isEmptyDate($value)) {
                            //$regEx = '#^[0-9]{1,2}[/\-\.][0-9]{1,2}[/\-\.][0-9]{4}#U';
                            //$this->addRule($column, ['date', ['regex', $regEx]], $this->getValidationMessage($column.'.date', 'Invalid date specified for '.$fieldName));
                            $this->addRule($column, 'date', $this->getValidationMessage($column.'.date', trans('db::validation.invalid_date')));
                        }
                        break;
                    case 'year':

                        //$regEx = (strlen(strval($value)) == 2) ? '#^\d{2}$#U' : '#^\d{4}$#U';
                        //$this->addRule($column, ['digits', ['regex', $regEx]], $this->getValidationMessage($column.'.date', 'Invalid date specified for '.$fieldName));
                        $this->addRule($column, 'digits', $this->getValidationMessage($column.'.date', trans('db::validation.invalid_date')));
                        break;
                }
            }
        }
    }
}