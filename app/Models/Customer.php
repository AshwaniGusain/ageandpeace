<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Snap\Database\DBUtil;

class Customer extends Model
{
    use SoftDeletes;
    use SpatialTrait;

    const ACTIVE_COLUMN = 'users.active';

    protected $fillable = [
        'user_id',
        'zip',
        'age'
    ];

    protected $with = ['user'];

    protected $displayNameField = 'name';

    public static $rules = [
        //'user_id' => 'required',
        'zip' => 'required',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $spatialFields = [
        'geo_point',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($customer) {
            $tasks = Task::all();

            foreach ($tasks as $task) {
                $task->issueTask($customer);
            }
        });

        static::deleting(function ($customer) {
            $customer->user->delete();

            foreach ($customer->tasks as $task) {
                $task->delete();
            }

            foreach ($customer->ratings as $rating) {
                $rating->delete();
            }
        });
    }

    public function user($includeInActive = true): BelongsTo
    {
        $belongsTo = $this->belongsTo('\App\Models\User', 'user_id');
        if ($includeInActive) {
            $belongsTo->withoutGlobalScopes();
        }
        return $belongsTo;
    }

    public function tasks()
    {
        return $this->hasMany('\App\Models\CustomerTask', 'customer_id');
    }

    public function ratings()
    {
        return $this->hasMany('\App\Models\Rating', 'customer_id');
    }

    public function scopeAge($query, $min, $max = null)
    {

    }

    public function scopeActive($query)
    {
        if (DBUtil::joinExists($query, 'users')) {
            return $query->where('users.active', 1);
        }
        return $query->whereHas('user', function ($query) {
            $query->where('active', 1);
        });
    }

    public function scopeInactive($query)
    {
        if (DBUtil::joinExists($query, 'users')) {
            return $query->where('users.active', 0);
        }
        return $query->whereHas('user', function ($query) {
            $query->where('active', 0);
        });
    }

    public function isActive()
    {
        return $this->user()->isActive();
    }

    public function progressByCategory($category, $asString = true) {
        $tasksQuery = $this->tasks()->ofCategory($category->slug);

        $totalCount = count($tasksQuery->get());
        $completedCount = count($tasksQuery->whereNotNull('completed_date')->get());

        if ($totalCount && $completedCount) {
            if ($asString) {
                $percentage = round(($completedCount / $totalCount) * 100, 0);
                return $percentage . '%';
            }

            return round(($completedCount / $totalCount), 2);
        }

        return 0;
    }

    public function getNameAttribute()
    {
        // This is added in case the property is joined to the model already.
        if ( ! empty($this->attributes['name'])) {
            return $this->attributes['name'];
        }

        $user = $this->user;

        if ($user) {
            return $this->user->name;
        }

        return null;
    }

    public function getEmailAttribute()
    {
        // This is added in case the property is joined to the model already.
        if ( ! empty($this->attributes['email'])) {
            return $this->attributes['email'];
        }

        $user = $this->user;

        if ($user) {
            return $this->user->email;
        }

        return null;
    }

    public function getDisplayNameAttribute()
    {
        $user = $this->user;

        if ($user) {
            return $this->user->name;
        }

        return null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        if ( ! empty($array)) {
            //$array['user.name']  = $array['user']['name'];
            //$array['user.email'] = $array['user']['email'];
            //$array['user.active'] = $array['user']['active'];
            unset($array['user']);
        }

        return $array;
    }

}
