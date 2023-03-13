<?php

namespace Snap\Meta\Models;

use Carbon\Carbon;
use Snap\Database\Model\Model;

class Meta extends Model
{
    protected $fillable = [
        'ref_id',
        'context',
        'name',
        'label',
        'type',
        'value',
        'locale',
    ];

    const INPUT_TYPE_STRING = 'string';

    const INPUT_TYPE_ARRAY = 'array';

    const INPUT_TYPE_JSON = 'json';

    const INPUT_TYPE_BOOL = 'bool';

    const INPUT_TYPE_NUMBER = 'number';

    const INPUT_TYPE_DATE = 'date';

    const INPUT_TYPE_OBJECT = 'object';

    protected $table = 'snap_meta';

    protected $casts = [
        //'data' => 'json',
    ];

    protected $meta = [];

    protected static function boot()
    {
        parent::boot();
        //static::saving(function ($model) {
        //    if (is_array($model->data)) {
        //        $model->data = json_encode($model->data);
        //    }
        //});
    }

    protected function hasMeta($meta) {
        $this->meta = $meta;

        return $this;
    }

    public function scopeFindByRefAndContext($query, int $refId, $context, $locale = null)
    {
        if ($locale) {
            $query
                ->where('ref_id', $refId)
                ->where('context', $context)
            ;
        }

        if ($locale) {
            $query->where('locale', $locale);
        }

        $data = $query->get()->keyBy('name');
        return $data;
    }

    public function findContextVars(int $refId, $context, $locale = null)
    {
        $data = $this->findByRefAndContext($refId, $context, $locale);
        $vars = $this->castMany($data);

        return $vars;
    }

    public function castMany($data)
    {
        $vars = [];

        foreach ($data as $d) {
            $vars[$d->name] = $this->cast($d->value, $d->type);
        }

        return $vars;
    }

    public static function cast($value, $type, $name = null)
    {
        $return = '';

        switch ($type) {
            case static::INPUT_TYPE_STRING:
                $return = $value;
                break;

            case static::INPUT_TYPE_NUMBER:
                $return = (float) $value;
                break;

            case static::INPUT_TYPE_BOOL:
                $return = is_true($value);
                break;

            case static::INPUT_TYPE_ARRAY:
            case static::INPUT_TYPE_OBJECT:
                if (is_string($value)) {
                    if ($json = is_json($value)) {
                        $return = $json;
                    }
                    elseif (is_serialized($value)) {
                        $return = unserialize($value);
                    }
                } else {
                    if (is_array($value)) {
                        $return = $value;
                    }
                }

                if (empty($return)) {
                    $return = [];
                }
                break;

            case static::INPUT_TYPE_DATE:
                $return = new Carbon($value);
                break;

            default:
                if ($type instanceof \Closure) {
                    $return = $type($value, $name);
                } elseif (is_string(class_exists($type)) && class_exists($type)) {
                    $return = new $type($value, $name);
                } else {
                    $return = $value;
                }
        }

        return $return;
    }

    public function scopeFindGlobalVars($query)
    {
        $data = $query->where('context', '*')->findAll();
        $vars = $this->castMany($data);

        return $vars;
    }

    public static function determineContentType($value)
    {
        if (is_array($value)) {
            return static::INPUT_TYPE_ARRAY;
        } elseif (is_json($value)) {
            return static::INPUT_TYPE_JSON;
        } elseif (is_object($value)) {
            return static::INPUT_TYPE_OBJECT;
        } elseif (is_date_format($value)) {
            return static::INPUT_TYPE_DATE;
        } elseif (is_int($value) OR is_float($value)) {
            return static::INPUT_TYPE_NUMBER;
        } elseif (is_true($value)) {
            return static::INPUT_TYPE_BOOLEAN;
        } else {
            return static::INPUT_TYPE_STRING;
        }
    }

    protected function getProcessValueTypeAttribute($value)
    {
        if (is_array($value) || $this->isJsonType()) {
            return static::INPUT_TYPE_JSON;
        } elseif ($this->isBooleanType()) {
            return static::INPUT_TYPE_BOOL;
        } elseif ($this->isNumberType()) {
            return static::INPUT_TYPE_NUMBER;
        } elseif ($this->isDateType()) {
            return static::INPUT_TYPE_DATE;
        } elseif ($this->isObjectType()) {
            return static::INPUT_TYPE_OBJECT;
        }

        return static::INPUT_TYPE_STRING;
    }

    protected function isArrayType()
    {
        return $this->getType() == 'array';
    }

    protected function isJsonType()
    {
        return $this->getType() == 'json';
    }

    protected function isDateType()
    {
        $validTypes = ['date', 'datetime', 'timestamp'];

        return in_array($this->getType(), $validTypes);
    }

    protected function isNumberType()
    {
        $validTypes = ['number', 'int', 'float'];

        return in_array($this->getType(), $validTypes);
    }

    protected function isBooleanType()
    {
        return $this->getType() == 'bool' || $this->getType() == 'boolean';
    }

    protected function isObjectType()
    {
        return $this->getType() == 'object';
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (is_array($value) || is_object($value)) ? json_encode($value) : $value;

        return $this;
    }

    public function getValueAttribute()
    {
        if (isset($this->attributes['value'])) {
            if (is_json($this->attributes['value'])) {
                return json_decode($this->attributes['value'], true);
            } else {
                return $this->attributes['value'];
            }
        }

        return null;
    }

}
