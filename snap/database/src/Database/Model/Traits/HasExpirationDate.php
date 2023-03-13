<?php

namespace Snap\Database\Model\Traits;

use Config;
use DateTime;
use DateTimeZone;

use Snap\Database\Model\Traits\Scopes\ExpirationDateScope;
// http://softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/

trait HasExpirationDate {

	/**
	 * Boot the scope.
	 * 
	 * @return void
	 */
	public static function bootHasExpirationDate()
	{
		// static::addGlobalScope(new ExpirationDateScope);
	}

	/**
	 * Get the name of the column for applying the scope.
	 * 
	 * @return string
	 */
	public function getExpirationDateColumn()
	{
		return defined('static::EXPIRATION_DATE_COLUMN') ? static::EXPIRATION_DATE_COLUMN : 'expiration_date';
	}

	/**
	 * Get the timezone.
	 * 
	 * @return string
	 */
	public function getExpirationDateTimezone()
	{
		return !empty($this->timezone) ? $this->timezone : Config::get('app.timezone');
	}

	/**
	 * Get the published value for applying the scope.
	 * 
	 * @return string
	 */
	public function getExpirationDateValue()
	{
		$outputTZ = $this->getExpirationDateTimezone();
		$inputTZ = $this->getExpirationDateTimezone();
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
	public function getQualifiedExpirationDateColumn()
	{
		return $this->getTable().'.'.$this->getExpirationDateColumn();
	}

	/**
	 * Get the query builder without the scope applied.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function withExpirationDate()
	{
		return with(new static)->newQueryWithoutScope(new ExpirationDateScope);
	}

}
