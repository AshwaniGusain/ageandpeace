<?php

namespace Snap\Navigation\Models;

use Cache;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Models\Contracts\RestorableInterface;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasHierarchy;
use Snap\Database\Model\Traits\HasStatus;
use Snap\Database\Model\Traits\HasUserInfo;
use Snap\Database\Model\Traits\IsRestorable;
use Snap\Database\Model\Traits\HasDisplayName;

class Navigation extends Model implements RestorableInterface
{
    use SoftDeletes;
    use HasStatus;
    use HasUserInfo;
    use HasHierarchy;
    use IsRestorable;
    use HasDisplayName;

    public static $rules = [
        'link' => 'required',
        'key' => 'required|unique:snap_navigation,key,{id}',
    ];

    protected static $booleans = [
        'hidden',
    ];

    protected $fillable = [
        'link',
        'key',
        'label',
        'parent_id',
        'group_id',
        'precedence',
        'attributes',
        'language',
        'hidden',
        'active',
        'published',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    protected $with = ['group'];

    protected $table = 'snap_navigation';

    public function group() : BelongsTo
    {
        return $this->belongsTo(NavigationGroup::class, 'group_id');
    }

    public function getDisplayNameAttribute()
    {
        if (!$this->label) {
            return $this->link;
        }

        return $this->label;
    }
}
