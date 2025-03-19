<?php

namespace RedJasmine\Support\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use Throwable;


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


    /**
     * @param Model $model
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
     * @param Model $model
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
