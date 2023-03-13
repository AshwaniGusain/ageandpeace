<?php namespace Snap\Database\Model\Traits\Scopes;

use Sofa\GlobalScope\GlobalScope;

use Illuminate\Database\Query\Builder as BaseBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Carbon\Carbon;


// http://softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/
// class PublishedScope extends GlobalScope {
class PublishDateScope implements Scope {

    // protected $whereIndex;

    // protected $bindingIndexes;
    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $column = $model->getQualifiedPublishDateColumn();

        $builder->where(function($query) use ($column, $model) {
            $query->where($column, '<=', $model->getPublishDateValue());
            $query->orWhereNull($column);
        });

        // $this->whereIndex = count($query->wheres) - 1;

        // $bindingCnt = count($query->getRawBindings()['where']);
        // $this->bindingIndexes[] = $bindingCnt - 3;
        // $this->bindingIndexes[] = $bindingCnt - 2;
        // $this->bindingIndexes[] = $bindingCnt - 1;

        $this->addWithPublishDate($builder);

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

        // $query = $builder->getQuery();

        // unset($query->wheres[$this->whereIndex]);
        // $whereBindings = $query->getRawBindings()['where'];

        // foreach($this->bindingIndexes as $binding)
        // {
        // 	unset($whereBindings[$binding]);
        // }
        // $query->setBindings(array_values($whereBindings));
        // $query->wheres = array_values($query->wheres);



        $query = $builder->getQuery();

        $column = $model->getQualifiedPublishDateColumn();

        $bindingKey = 0;

        foreach ((array) $query->wheres as $key => $where)
        {
            if ($this->isPublishDateConstraint($where, $column, $model->getPublishDateValue()))
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
        unset($bindings[$key + 1]);
        unset($bindings[$key + 2]);
        $query->setBindings($bindings);
    }

    /**
     * Check if given where is the scope constraint.
     *
     * @param  array   $where
     * @param  string  $column
     * @return boolean
     */
    protected function isPublishDateConstraint(array $where, $column, $value)
    {
        $now = (new Carbon())->format('Y-m-d H:i:s');
        if ($where['type'] == 'Nested' && isset($where['query']))
        {
            $wheres = $where['query']->wheres;
            if (
                $where['query']->wheres[0]['column'] == $column && $wheres[0]['type'] == 'Basic' && $wheres[0]['value'] <= $now
                &&
                $where['query']->wheres[1]['column'] == $column && $wheres[1]['type'] == 'Null' && $value != null
            )
            {
                return true;
            }

        }
        return false;
        //return ($where['type'] == 'Nested' && $where['column'] == $column && $where['value'] <= date('Y-m-d H:i:s'));
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     */
    protected function addWithPublishDate(Builder $builder)
    {
        $builder->macro('withoutPublishDate', function(Builder $builder)
        {
            $this->remove($builder, $builder->getModel());

            return $builder;
        });
    }

}
