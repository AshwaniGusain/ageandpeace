<?php

namespace Snap\Database\Model\Traits;

trait HasStatus {

	/**
	 * Get the name of the column for applying the scope.
	 * 
	 * @return string
	 */
	public function getStatusColumn()
	{
		return defined('static::STATUS_COLUMN') ? static::STATUS_COLUMN : 'status';
	}

	public function getStatusList()
	{
		$info = $this->columnInfo($this->getStatusColumn());
		$labels = collect($info['options'])->map(function($item) {
			return ucfirst($item);
		});
		return collect($info['options'])->combine($labels);
	}

	public function getStatusAttribute()
	{
		if ($this->exists) {
			return $this->attributes[$this->getStatusColumn()];	
		}
	}

	public function isStatus($status)
	{
		return $this->getStatusAttribute() == $status;
	}
}
