<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Traits\HasActive;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Task extends Model implements HasMedia
{
    use HasActive;
    use HasMediaTrait;

    public static $rules = [
        'title'       => 'required',
        //'description' => 'required',
        'file'        => 'mimes:pdf',
    ];

    protected $fillable = [
        'title',
        'description',
        'active',
        'category_id',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'category'
    ];

    protected $dates = ['deleted_at'];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('task-files')->singleFile()->acceptsFile(function (File $file) {
            return $file->mimeType === 'application/pdf' || $file->mimeType === 'application/zip';
        })
        ;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo('\App\Models\Category');
    }

    public function customerTasks(): HasMany
    {
        return $this->hasMany('\App\Models\CustomerTask', 'task_id');
    }

    public function providerType(): BelongsTo
    {
        return $this->belongsTo('\App\Models\ProviderType');
    }


    public function file(): MorphMany
    {
        $morphMany = $this->morphMany('Snap\Media\Models\Media', 'model');
        $morphMany->where('collection_name', '=', 'task-files');

        return $morphMany;
    }

    public function issueTask(Customer $customer)
    {
        $customerTask = new \App\Models\CustomerTask();
        //$customerTask->title = $this->title;
        //$customerTask->description = $this->description;
        //$customerTask->category_id = $this->category_id;
        $customerTask->task_id = $this->id;

        $customer->tasks()->save($customerTask);

        return true;
    }
}
