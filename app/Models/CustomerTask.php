<?php

namespace App\Models;

use Carbon\Carbon;
use Snap\Admin\Modules\ModuleModel as Model;

class CustomerTask extends Model
{
    protected $fillable = ['title', 'description', 'category_id', 'task_id'];

    protected $dates = [
        'due_date',
        'completed_date'
    ];

    protected $appends = [
        'categoryClass',
        'title',
        'description',
        'file',
        'category_id',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'task',
    ];

    //protected $with = ['task'];

    public function customer()
    {
        return $this->belongsTo('\App\Models\Customer', 'customer_id');
    }

    public function task()
    {
        return $this->belongsTo('\App\Models\Task', 'task_id');
    }

    public function scopeUpcoming($query, $date = null)
    {
        if (is_null($date)) {
            $date = Carbon::today()->addWeek();
        }

        return $query->where('due_date', '<', $date);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }

    public function scopeOfCategory($query, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            $ids = $category->allChildren();

            return $query->whereHas('task', function ($q) use ($ids) {
                $q->whereIn('category_id', $ids);
            });
        }
    }

    public function scopeOfProviderType($query, $providerType)
    {
        if (is_string($providerType)) {
            $providerType = ProviderType::where('slug', $providerType)->first();
        } elseif (is_int($providerType)) {
            $providerType = ProviderType::where('id', $providerType)->first();
        }

        if ($providerType) {
            return $query->whereHas('task', function ($q) use ($providerType) {
                $q->where('provider_type_id', $providerType->id);
            });
        }

        return $query;
    }

    public function scopeOfUser($query, $id)
    {
        return $query->where('customer_id', $id);
    }

    public function getCategoryAttribute()
    {
        return $this->task->category;
    }

    public function getCategoryIdAttribute()
    {
        return $this->task->category->id;
    }

    public function getCategoryClassAttribute() {
        if (is_null($this->category->parent)) {
            return $this->category->slug;
        }
        return $this->category->highestParent()->slug;
    }

    public function getDueDateAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        return date('m/d/Y', strtotime($value));
    }

    public function getCompletedDateAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        return date('m/d/Y', strtotime($value));
    }

    public function getTitleAttribute()
    {
        return $this->task->title;
    }

    public function getDescriptionAttribute()
    {
        return $this->task->description;
    }

    public function getFileAttribute()
    {
        return $this->task->file;
    }

}
