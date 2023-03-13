<?php

namespace Snap\Page\Models;

use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;
use Snap\Database\Model\Traits\HasUserInfo;
use Snap\Meta\Relationships\MetaFields;

class Site extends Model
{
    use HasActive;
    use HasUserInfo;

    public static $rules = [
        'name' => 'required',
    ];

    protected static $booleans = [
        'active',
    ];

    protected $fillable = [
        'name',
        'active',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    protected $table = 'snap_sites';

    public function meta(): MetaFields
    {
        $fields = [];
        if ($this->exists) {
            foreach ($this->template->inputs() as $key => $field) {
                $fields[$key] = $field;
            }

        }
        return $this->metaFields($fields);
    }

    public function getTemplateAttribute()
    {
        return \Template::get($this->type);
    }
}