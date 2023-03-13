<?php

namespace Snap\Database\Model;

use Snap\Database\Model\Traits\HasUserInfo;

class Archive extends Model
{
    use HasUserInfo;

    protected $table = 'snap_archives';

    protected $casts = [
        'data' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (is_array($model->data)) {
                $model->data = json_encode($model->data);
            }
        });
    }

    public function setDataAttribute($data)
    {
        $this->attributes['data'] = (is_array($data)) ? json_encode($data) : $data;

        return $this;
    }

    public function getDataAttribute($data)
    {
        return json_decode($this->attributes['data'], true);
    }

}
