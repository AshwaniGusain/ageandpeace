<?php

namespace Snap\Database\Model\Traits;

use Snap\Database\Model\Traits\Scopes\PublishedScope;
// http://softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/

trait HasPublished {

	/**
	 * Boot the scope.
	 * 
	 * @return void
	 */
	public static function bootHasPublished()
	{
		static::addGlobalScope(new PublishedScope);
	}

	/**
	 * Get the name of the column for applying the scope.
	 * 
	 * @return string
	 */
	public function getPublishedColumn()
	{
		return defined('static::PUBLISHED_COLUMN') ? static::PUBLISHED_COLUMN : 'published';
	}

	/**
	 * Get the published value for applying the scope.
	 * 
	 * @return string
	 */
	public function getPublishedValue()
	{
		return defined('static::PUBLISHED_VALUE') ? static::PUBLISHED_VALUE : 'yes';
	}

	/**
	 * Get the fully qualified column name for applying the scope.
	 * 
	 * @return string
	 */
	public function getQualifiedPublishedColumn()
	{
		return $this->getTable().'.'.$this->getPublishedColumn();
	}

	/**
	 * Get the query builder without the scope applied.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function withDrafts()
	{
		return with(new static)->newQueryWithoutScope(new PublishedScope);
	}

}
