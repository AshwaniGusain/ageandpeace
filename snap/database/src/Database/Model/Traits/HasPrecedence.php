<?php

namespace Snap\Database\Model\Traits;

use Snap\Database\Model\Traits\Scopes\PrecedenceScope;

trait HasPrecedence {

	/**
	 * Boot the scope.
	 * 
	 * @return void
	 */
	 public static function bootHasPrecedence()
	 {
	 	static::addGlobalScope(new PrecedenceScope);
	 }

	/**
	 * Get the name of the precedence column for applying the scope.
	 * 
	 * @return string
	 */
	public function getPrecedenceColumn()
	{
		return defined('static::PRECEDENCE_COLUMN') ? static::PRECEDENCE_COLUMN : 'precedence';
	}

	/**
	 * Get the precedence order by value for applying the scope.
	 * 
	 * @return string
	 */
	public function getPrecedenceOrderBy()
	{
		return defined('static::PRECEDENCE_ORDER_BY') ? static::PRECEDENCE_ORDER_BY : 'asc';
	}

	/**
	 * Orders the data based on the precedence value.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithPrecedence($builder)
	{
		$model = $builder->getModel();
		return $builder->orderBy($model->getPrecedenceColumn(), $model->getPrecedenceOrderBy());
	}

	// /**
	//  * Get a new query builder that excludes the order by for precedence.
	//  *
	//  * @return \Illuminate\Database\Eloquent\Builder|static
	//  */
	// public static function withoutPrecedence()
	// {
	// 	// $builder->withoutGlobalScope(PrecedenceScope::class);
	// 	return (new static)->newQueryWithoutScope(new PrecedenceScope);
	// }

}
