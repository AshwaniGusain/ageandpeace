<?php

namespace Snap\Setting\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasUserInfo;

class Setting extends Model
{
    use HasUserInfo;

    public static $rules = [
        'key' => 'required',
    ];

    protected static $booleans = [
        'hidden',
    ];

    protected $fillable = [
        'key',
        'label',
        'value',
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    protected $table = 'snap_settings';

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (is_array($model->value)) {
                $model->value = json_encode($model->value);
            }
        });
    }
}
