<?php

namespace Snap\Admin\Models;

use Snap\Database\DBUtil;
use Snap\Database\Model\Model;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'data',
        'user_id',
    ];

    protected $table = 'snap_logs';

    public static $rules = [
        'level'  => 'required',
        'message' => 'required',
        'data' => 'required',
    ];

    protected $displayNameField = 'message';

    public function user()
    {
        return $this->belongsTo('\Snap\Admin\Models\User', 'user_id');
    }

}
