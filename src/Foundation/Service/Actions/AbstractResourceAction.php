<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Illuminate\Database\Eloquent\Model;

use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use RedJasmine\Support\Foundation\Pipelines\ModelWithOperator;
use RedJasmine\Support\Foundation\Service\Actions;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;
use ReflectionClass;

/**
 * @property Data|null       $data
 * @property ResourceService $service
 */
abstract class AbstractResourceAction extends Actions
{


    protected function conversionData(Data|array $data) : Data
    {
        if (is_array($data)) {
            $data['owner'] = $this->service->getOwner()->toArray();
            // dd($this->service->getOwner());
            try {
                $data = (new ReflectionClass($this))->getProperty('data')->getType()->getName()::from($data);
            } catch (\ReflectionException) {
                $data = $this->service::getDataClass()::from($data);
            }
        }


        return $data;
    }

    protected static array $commonPipes = [
        ModelWithOperator::class,
    ];


    public ?Model $model = null;

    public int|string|null $key = null;

    public function init() : void
    {
        if ($this->key) {
            $query       = $this->service::getModel()::query();
            $this->model = $this->service->callQueryCallbacks($query)->findOrFail($this->key);
        } else {
            $this->model = app($this->getModel());
        }
    }


    public function save() : Model
    {
        // 初始化管道
        $this->initPipelines($this);
        try {
            $this->beginDatabaseTransaction();
            // 初始化
            $this->pipelines->call('init', fn() => $this->init());
            // 验证
            $this->pipelines->call('validate', fn() => $this->validate());
            // 填充
            $this->pipelines->call('fill', fn() => $this->fill());
            // 处理
            $this->pipelines->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->pipelines->call('after', fn() => $this->after());
    }

    public function delete() : bool|null
    {
        // 初始化管道
        // 初始化管道
        $this->initPipelines($this);
        try {
            $this->beginDatabaseTransaction();
            // 初始化
            $this->pipelines->call('init', fn() => $this->init());
            // 处理
            $this->pipelines->call('handle', fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->pipelines->call('after', fn() => $this->after());

    }


    protected function getModel() : string
    {
        return $this->service::getModel();
    }


    public function validate() : array
    {
        // 验证数据
        $this->data->toArray();
        return $this->data->toArray();
    }

    public function fill() : void
    {
        $this->fillData();
    }


    public function after() : Model
    {
        return $this->model;
    }


    /**
     * 填充数据
     * @return void
     */
    protected function fillData() : void
    {
        if ($this->data instanceof Data) {
            $this->model->fill($this->data->toArray());
            if ($this->data->owner instanceof UserData) {
                $this->model->owner = $this->data->owner;
            }
        }
    }

}
