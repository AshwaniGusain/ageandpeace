<?php

namespace Snap\Database\Model\Traits;

use Config;
use DateTime;
use DateTimeZone;

use Snap\Database\Model\Traits\Scopes\PublishDateScope;
// http://softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/
trait HasPublishDate {

	/**
	 * Boot the scope.
	 * 
	 * @return void
	 */
	public static function bootHasPublishDate()
	{
		static::addGlobalScope(new PublishDateScope);
	}

	/**
	 * Get the name of the column for applying the scope.
	 * 
	 * @return string
	 */
	public function getPublishDateColumn()
	{
		return defined('static::PUBLISH_DATE_COLUMN') ? static::PUBLISH_DATE_COLUMN : 'publish_date';
	}

	/**
	 * Get the timezone.
	 * 
	 * @return string
	 */
	public function getPublishDateTimezone()
	{
		return !empty($this->timezone) ? $this->timezone : Config::get('app.timezone');
	}

	/**
	 * Get the published value for applying the scope.
	 * 
	 * @return string
	 */
	public function getPublishDateValue()
	{
		$outputTZ = $this->getPublishDateTimezone();
		$inputTZ = $this->getPublishDateTimezone();
		$dt = new DateTime(null, new DateTimeZone($outputTZ)); //first argument "must" be a string
		$dt->setTimezone(new DateTimeZone($inputTZ)); //adjust the object to correct timestamp
		$value = $dt->format('Y-m-d H:i:s');
		return $value;
	}

	/**
	 * Get the fully qualified column name for applying the scope.
	 * 
	 * @return string
	 */
	public function getQualifiedPublishDateColumn()
	{
		return $this->getTable().'.'.$this->getPublishDateColumn();
	}

	/**
	 * Get the query builder without the scope applied.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function withoutPublishDate()
	{
		return with(new static)->newQueryWithoutScope(new PublishDateScope);
	}

}
