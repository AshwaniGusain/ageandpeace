<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snap\Admin\Modules\ModuleModel as Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Company extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];

    public static $rules = [
        'name'  => 'required',
    ];

    public static $unique = [
        'slug',
    ];

	public function providers() : HasMany
    {
		return $this->hasMany( '\App\Models\Provider', 'company_id');
	}

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }
}
