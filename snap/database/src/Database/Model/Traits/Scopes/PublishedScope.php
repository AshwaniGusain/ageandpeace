<?php namespace Snap\Database\Model\Traits\Scopes;

use Sofa\GlobalScope\GlobalScope;

use Illuminate\Database\Query\Builder as BaseBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

// http://softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/
// class PublishedScope extends GlobalScope {
class PublishedScope implements Scope {
 

	/**
	 * Apply scope on the query.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function apply(Builder $builder, Model $model)
	{
		$column = $model->getQualifiedPublishedColumn();

		$builder->where($column, '=', $model->getPublishedValue());

		$this->addWithDrafts($builder);
	}

	// public function isScopeConstraint(array $where, Model $model)
	// {
	// 	return true;
	// }
	
	/**
	 * Remove scope from the query.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 * @param \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function remove(Builder $builder, Model $model)
	{
		$query = $builder->getQuery();

		$column = $model->getQualifiedPublishedColumn();

		$bindingKey = 0;

		foreach ((array) $query->wheres as $key => $where)
		{
			if ($this->isPublishedConstraint($where, $column, $model->getPublishedValue()))
			{
				$this->removeWhere($query, $key);

				// Here SoftDeletingScope simply removes the where
				// but since we use Basic where (not Null type)
				// we need to get rid of the binding as well
				$this->removeBinding($query, $bindingKey);
			}

			// Check if where is either NULL or NOT NULL type,
			// if that's the case, don't increment the key
			// since there is no binding for these types
			if ( ! in_array($where['type'], ['Null', 'NotNull'])) $bindingKey++;
		}
	}

	/**
	 * Remove scope constraint from the query.
	 * 
	 * @param  \Illuminate\Database\Query\Builder  $builder
	 * @param  int  $key
	 * @return void
	 */
	protected function removeWhere(BaseBuilder $query, $key)
	{
		unset($query->wheres[$key]);

		$query->wheres = array_values($query->wheres);
	}

	/**
	 * Remove scope constraint from the query.
	 * 
	 * @param  \Illuminate\Database\Query\Builder  $builder
	 * @param  int  $key
	 * @return void
	 */
	protected function removeBinding(BaseBuilder $query, $key)
	{
		$bindings = array_values($query->getRawBindings()['where']);

		unset($bindings[$key]);

		$query->setBindings($bindings);
	}
	
	/**
	 * Check if given where is the scope constraint.
	 * 
	 * @param  array   $where
	 * @param  string  $column
	 * @return boolean
	 */
	protected function isPublishedConstraint(array $where, $column, $value)
	{
		return ($where['type'] == 'Basic' && $where['column'] == $column && $where['value'] == $value);
	}

	/**
	 * Extend Builder with custom method.
	 * 
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 */
	protected function addWithDrafts(Builder $builder)
    {
        $builder->macro('withDrafts', function(Builder $builder)
        {
            $this->remove($builder, $builder->getModel());
 
            return $builder;
        });
    }

}
