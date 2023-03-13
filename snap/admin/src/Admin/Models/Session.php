<?php

namespace Snap\Admin\Models;

use Snap\Database\Model\Model;

class Session extends Model
{
    protected $table = 'sessions';
    protected $dates = ['last_activity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
