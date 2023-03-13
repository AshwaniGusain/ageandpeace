<?php namespace Snap\Database\Model\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PrecedenceScope implements Scope {

 	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function apply(Builder $builder, Model $model)
	{
		$this->applyQuery($builder, $model);
	}

	/**
	 * Extend the query builder with the needed functions.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function extend(Builder $builder)
	{
		$this->addWithPrecedence($builder);
		$this->addWithoutPrecedence($builder);
	}

	/**
	 * Extend Builder with custom method.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 */
	protected function addWithoutPrecedence(Builder $builder)
	{
		$builder->macro('withoutPrecedence', function(Builder $builder){
			return $builder->withoutGlobalScope($this);
		});
	}

	/**
	 * Extend Builder with custom method.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 */
	protected function addWithPrecedence(Builder $builder)
	{
		$builder->macro('withPrecedence', function(Builder $builder){
			return $this->applyQuery($builder);
		});
	}

    /**
     * Apply the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
	protected function applyQuery(Builder $builder, Model $model = null)
	{
		if ( ! $model) $model = $builder->getModel();
		return $builder->orderBy($model->getPrecedenceColumn(), $model->getPrecedenceOrderBy());
	}

}
