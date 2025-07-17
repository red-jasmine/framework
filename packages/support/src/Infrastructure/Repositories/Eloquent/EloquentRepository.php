<?php

namespace RedJasmine\Support\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use Throwable;


/**
 * @template TClass of Model
 */
class EloquentRepository implements RepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string<TClass>
     */
    protected static string $eloquentModelClass = Model::class;


    public function find($id)
    {
        return static::$eloquentModelClass::findOrFail($id);
    }

    public function findLock($id)
    {
        return static::$eloquentModelClass::findOrFail($id);
    }


    public function findByNo(string $no)
    {
        return static::$eloquentModelClass::uniqueNo($no)->firstOrFail();
    }

    public function findByNoLock(string $no)
    {
        return static::$eloquentModelClass::lockForUpdate()->uniqueNo($no)->firstOrFail();
    }


    /**
     * @param  Model  $model
     *
     * @return mixed
     * @throws Throwable
     */
    public function store(Model $model) : Model
    {
        $model->push();
        return $model;
    }

    /**
     * @param  Model  $model
     *
     * @return void
     * @throws Throwable
     */
    public function update(Model $model) : void
    {
        $model->push();
    }

    public function delete(Model $model)
    {
        $model->delete();
    }


}
