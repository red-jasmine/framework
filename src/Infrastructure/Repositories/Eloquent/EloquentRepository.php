<?php

namespace RedJasmine\Support\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use Throwable;

class EloquentRepository implements RepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Model::class;

    public function find($id)
    {
        return static::$modelClass::findOrFail($id);
    }

    /**
     * @param Model $model
     *
     * @return mixed
     * @throws Throwable
     */
    public function store(Model $model) : Model
    {
        try {
            DB::beginTransaction();
            $model->push();
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $model;
    }

    public function update(Model $model)
    {
        try {
            DB::beginTransaction();
            $model->push();
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }

    public function delete(Model $model)
    {
        $model->delete();
    }


}
