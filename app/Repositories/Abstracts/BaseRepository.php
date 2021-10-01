<?php

namespace App\Repositories\Abstracts;

use App\Exceptions\RepositoryException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

/**
 * abstract class BaseRepositoryAbstract
 *
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;


    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return Class
     */
    abstract public function model();

    /**
     * Filter data based on user input
     *
     * @param array $filter
     * @param       $query
     */
    abstract public function filterData(array $filter, $query);

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data)
    {
        $model = $this->model->newInstance($data);
        $model->save();
        return $model;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $data, $id)
    {
        $query = $this->newQuery();
        $model = $query->findOrFail($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param $id
     *
     * @return bool|int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * Get paginated filtered data.
     *
     * @param array $filter
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(array $filter, array $columns = ['*'], $perPage = 10)
    {
        return $this->getFilterQuery($filter)->paginate($perPage, $columns);
    }

    /**
     * Get all filtered data.
     *
     * @param array $filter
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $filter, array $columns = ['*'], $skip = null, $limit = null)
    {
        $query = $this->getFilterQuery($filter);

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }
        return $query->get($columns);
    }

    /**
     * Get query filtered data.
     *
     * @param array $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilterQuery(array $filter)
    {
        $query = $this->newQuery();

        if (!empty($filter)) {
            $this->filterData($filter, $query);
        }

        return $query;
    }

    /**
     * @param string $attribute
     * @param string|int $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findBy(string $attribute, string|int $value, array $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    public function makeModel()
    {
        return $this->setModel($this->model());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->model->newQuery();
    }

    /**
     * Set Eloquent Model to instantiate
     *
     * @param $eloquentModel
     *
     * @return Model
     * @throws RepositoryException|\Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setModel($eloquentModel)
    {
        $model = $this->app->make($eloquentModel);

        if (!$model instanceof Model) {
            throw new Exception("Class {$model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

}
