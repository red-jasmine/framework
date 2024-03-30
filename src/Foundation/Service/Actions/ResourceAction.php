<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Product\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Support\Foundation\Service\HasData;
use RedJasmine\Support\Foundation\Service\HasModel;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Throwable;

/**
 * @property Data|null       $data
 * @property ResourceService $service
 * @property Model           $model
 * @method  handle
 */
abstract class ResourceAction extends Action
{
    use HasModel;

    use HasData;


    public function delete() : bool|null
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            $handleResult = $this->getPipelines()->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->getPipelines()->call('after', fn() => $this->after($handleResult));

    }

    public function forceDelete() : bool|null
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            $handleResult = $this->getPipelines()->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->getPipelines()->call('after', fn() => $this->after($handleResult));
    }

    public function restore() : bool|null
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            $handleResult = $this->getPipelines()->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->getPipelines()->call('after', fn() => $this->after($handleResult));
    }


    /**
     * @return Model
     * @throws Throwable
     */
    protected function store() : Model
    {
        return $this->save();
    }

    /**
     * @throws Throwable
     */
    protected function save() : Model
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            // 数据转换 对象
            $this->data = $this->getPipelines()->call('init', fn() => $this->init($this->data));
            // 验证 获取验证后的值
            $this->makeValidator($this->data->toArray());
            $data = $this->getPipelines()->call('validate', fn() => $this->validate());
            //
            $this->getPipelines()->call('fill', fn() => $this->fill($data));
            // 存储数据
            $handleResult = $this->getPipelines()->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->getPipelines()->call('after', fn() => $this->after($handleResult));
    }

    protected bool $lockForUpdate = false;

    protected function resolveModel() : void
    {
        if ($this->key) {
            $query = $this->service->callQueryCallbacks($this->getModelClass()::query());
            if ($this->lockForUpdate) {
                $query->lockForUpdate();
            }
            $this->model = $query->findOrFail($this->key);

        } else {
            $this->model = app($this->getModelClass());
        }
    }

    protected function authorizeAccess() : void
    {


    }

    protected function init($data) : Data
    {

        return $this->conversionData($data);
    }

    protected function validate() : array
    {
        if ($this->getValidator()) {
            $this->getValidator()->validate();
            return $this->getValidator()->safe()->all();
        }
        return $this->data->toArray();
    }

    /**
     * @param array $data
     *
     * @return Model|null
     * @throws Exception
     */
    protected function fill(array $data) : ?Model
    {
        // TODO 这里需要改造
        $this->generateId($this->model);
        $this->model->fill($data);
        // TODO 改造不依赖service
        if ($this->service::$autoModelWithOwner) {
            $this->model->{$this->service::$modelOwnerKey} = $this->data->owner ?? $this->service->getOwner() ?? null;
        }
        return $this->model;
    }

    /**
     * @param Model $model
     *
     * @return void
     * @throws Exception
     */
    protected function generateId(Model $model) : void
    {
        if ($model->exists === false && $model->incrementing === false) {
            $model->{$this->model->getKeyName()} = $this->service::buildID();
        }
    }

    protected function after($handleResult) : mixed
    {
        return $handleResult;
    }

    protected function update() : Model
    {
        return $this->save();
    }


}
