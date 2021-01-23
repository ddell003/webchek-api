<?php


namespace App\Repository;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Builder;

abstract class EloquentRepository extends Repository
{
    use EloquentBuilderTrait {
        applyFilter as protected nullIssueApplyFilter;
    }

    /**
     * Checks whether or not a filter key exists on the given array
     * @param array $options
     * @param string $key
     * @return bool
     */
    protected function filterKeyExists(array $options, string $key): bool
    {
        foreach ($options['filter_groups'] ?? [] as $group) {
            $filters = array_merge($filters ?? [], $group['filters'] ?? []);
        }

        if (isset($filters)) {
            $filterFindFunc =
                function ($item) use ($key) {
                    return $item['key'] == $key;
                };

            return collect($filters)->find($filterFindFunc) !== null;
        }
        else {
            return false;
        }
    }

    /**
     * Checks whether or not a sort key exists on the given array
     * @param array $options
     * @param string $key
     * @return bool
     */
    protected function sortKeyExists(array $options, string $key): bool
    {
        foreach ($options['sort'] ?? [] as $sort) {
            if (Arr::get($sort, 'key') == $key) {
                return true;
            }
        }
        return false;
    }

    protected function applyFilter(Builder $queryBuilder, array $filter, $or = false, array &$joins)
    {
        $value = $filter['value'];
        $not = $filter['not'];
        $key = $filter['key'];
        $table = $queryBuilder->getModel()->getTable();

        if ($value === 'null' || $value === '') {
            $method = $not ? 'WhereNotNull' : 'WhereNull';
            if ($or) {
                $method = 'or'.$method;
            }

            /*
             * Bruno currently doesn't support custom methods for null values...
             */
            $customFilterMethod = $this->hasCustomMethod('filter', $key);
            if ($customFilterMethod) {
                call_user_func_array([$this, $customFilterMethod], [
                    $queryBuilder,
                    $method,
                    null,
                    $key
                ]);
            } else {
                call_user_func([$queryBuilder, $method], sprintf('%s.%s', $table, $key));
            }
        } else {
            // these are a === true instead of truthy checks
            $or = boolval($or);
            $filter['not'] = boolval($not);

            $this->nullIssueApplyFilter($queryBuilder, $filter, $or, $joins);
        }
    }

    /**
     * Since we're using "relational filters" in a few places, this breaks bruno custom filters.
     * This is a simply method to fix sorts for existing custom filters.
     * @param array $options
     */
    final protected function fixCustomSorts(array &$options)
    {
        // Remove container notation if there's a custom sort for the column
        if (array_key_exists('sort', $options) && !empty($options['sort'])) {
            foreach ($options['sort'] as &$sort) {
                $parts = explode('.', $sort['key']);
                $key = array_pop($parts);
                $method = sprintf('%s%s', 'sort', Str::studly($key));
                if (method_exists($this, $method)) {
                    $sort['key'] = $key;
                }
            }
        }
    }

    protected function createBaseBuilder(array $options = [])
    {
        $this->fixCustomSorts($options);

        $query = $this->createQueryBuilder();

        $this->applyResourceOptions($query, $options);

        $model = $this->getModel();

        $traits = class_uses(get_class($model), true);
        if (in_array(SoftDeletes::class, $traits)) {
            $deletedAtKey = $model->getDeletedAtColumn();
            $filter_groups = Arr::get($options, 'filter_groups', []);
            foreach ($filter_groups as $filter_group) {
                $filters = Arr::get($filter_group, 'filters', []);
                foreach ($filters as $filter) {
                    $key = Arr::get($filter, 'key');
                    $negating = Arr::get($filter, 'not');

                    if ($key == $deletedAtKey && $negating) {
                        $query->withTrashed();
                    }
                }
            }
        }

        if (empty($options['sort'])) {
            $this->defaultSort($query, $options);
        }

        return $query;
    }

    /**
     * Delete a resource by its primary key
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        $query = $this->createQueryBuilder();
        $query->where($this->getPrimaryKey($query), $id);

        //cast query to a variable and then call method, other wise deleted observer will not get called
        $item = $query->first();
        if ($item) {
            $item->delete();
        }
    }

    public function updateWhere($column, $value, $data)
    {
        $query = $this->createQueryBuilder();

        $query->where($column, '=', $value)->update($data);
    }

    public function getItem($id, $options, $trashed = 0)
    {
        $query = $this->createBaseBuilder($options);

        if($trashed){
            $query->withTrashed();
        }

        return $query->findOrFail($id);

    }

    /**
     * Creates an object
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->getModel()->create($data);
    }

    public function update($id, $data)
    {
        $model = $this->getModel()->findOrFail($id);
        $model->update($data);
        return $model;
    }

    /**
     * Inserts an array of objects
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->getModel()->insert($data);
    }

    /**
     * Tries to find a model and if it can't, creates a new one.
     * @param array $data
     * @return mixed
     */
    public function firstOrCreate(array $data)
    {
        return $this->getModel()->firstOrCreate($data);
    }

    /* i made this because I got didn't want to fight with what I needed to use to create an instance and what I wanted to match off of.
     * also to be able to use LIKE instead of = when needed
     */
    public function optionsFindOrCreate(array $options, $create)
    {
        /* @var Builder $builder */
        $builder = $this->getModel()->newQuery();
        $builder = $this->applyResourceOptions($builder, $options);
        $found = $builder->first();
        if ($found) {
            return $found;
        }
        return $this->create($create);
    }

    protected function getByIdBuilder(array $options = [])
    {
        $query = $this->createBaseBuilder($options);
        /** @var Model $model */
        $model = $query->getModel();
        if (in_array(Archivable::class, class_uses($model))) {
            $query->withArchived();
        }
        return $query;
    }

    public function getById($id, array $options = [])
    {
        return $this->getByIdBuilder($options)->find($id);
    }

    public function getByIdOrFail($id, array $options = [])
    {
        return $this->getByIdBuilder($options)->findOrFail($id);
    }

}
