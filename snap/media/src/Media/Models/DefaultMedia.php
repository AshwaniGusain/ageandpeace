<?php

namespace Snap\Media\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;
//use Snap\Admin\Modules\ModuleModel as Model;
use Snap\Database\Model\Model;
use Snap\Database\Model\Traits\HasDisplayName;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\MediaObserver;

class DefaultMedia extends Model implements HasMedia
{
    use HasDisplayName;
    use HasMediaTrait; // Implement HasMedia if used

    protected $table = 'snap_default_media';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    //protected $displayNameField = 'file_name';
    //
    ///**
    // * Validation rules.
    // *
    // * @var array
    // */
    //public static $rules = [
    //    'model_type' => 'required',
    //    'model_id' => 'required',
    //];

    public function getDisplayNameAttribute()
    {
        return $this->media()->first()->file_name;
    }
}
