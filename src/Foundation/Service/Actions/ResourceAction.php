<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Services\Product\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use RedJasmine\Support\Foundation\Pipelines\ModelWithOperator;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Support\Foundation\Service\HasValidatorCombiners;
use RedJasmine\Support\Foundation\Service\ResourceService;
use ReflectionClass;

/**
 * @property Data|null       $data
 * @property ResourceService $service
 * @method  handle
 */
abstract class ResourceAction extends Action
{


    // 操作方法名

    // 权限验证策略 // TODO 发放在这一层
    // 操作是否需要事务
    // 模型类
    // 能力 可以静态外部扩展
    // 能力 可以配置重置
    // 能力 可以实例添加
    //  有管道能力 管道 List

    // 数据对象类   DataClass
    //  有 验证组合器  能力 List
    // 事件

    // 入参 key,Data
    // 数据持久化   定义接口
    // 返回值


    /**
     * @var ?string
     */
    protected ?string $dataClass = null;


    protected static ?string $validatorManageClass = null;

    protected static array $commonPipes = [
        ModelWithOperator::class,
    ];

    public ?Model $model = null;

    public int|string|null $key = null;

    protected function resolveModel() : void
    {
        if ($this->key) {
            $query       = $this->service::getModel()::query();
            $this->model = $this->service->callQueryCallbacks($query)->findOrFail($this->key);
        } else {
            $this->model = app($this->getModel());
        }
    }


    protected function authorizeAccess() : void
    {


    }

    protected function store() : Model
    {
        return $this->save();
    }

    protected function update() : Model
    {
        return $this->save();
    }

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

    protected function save() : Model
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            // 数据转换 对象
            $this->data = $this->getPipelines()->call('init', fn() => $this->init($this->data));
            // 验证 获取验证后的值
            $this->getValidator();
            $data = $this->getPipelines()->call('validate', fn() => $this->validate());
            // 填充 model
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


    protected function getModel() : string
    {
        return $this->service::getModel();
    }

    protected function init($data) : Data
    {
        return $this->conversionData($data);
    }


    protected ?Validator $validator = null;

    public function getValidator() : ?Validator
    {
        return $this->validator = $this->validator ?? $this->combinerValidator(\Illuminate\Support\Facades\Validator::make($this->data->toArray(), []));
    }

    public function setValidator(?Validator $validator) : ResourceAction
    {
        $this->validator = $validator;
        return $this;
    }

    protected function validate() : array
    {
        $this->getValidator()->validate();
        return $this->getValidator()->safe()->all();
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

    /**
     * @param array $data
     *
     * @return Model|null
     * @throws Exception
     */
    protected function fill(array $data) : ?Model
    {
        $this->generateId($this->data);
        $this->model->fill($data);
        if ($this->service::$autoModelWithOwner) {
            $this->model->{$this->service::$modelOwnerKey} = $this->data->owner ?? $this->service->getOwner() ?? null;
        }
        return $this->model;
    }


    protected function after($handleResult) : mixed
    {
        return $handleResult;
    }

    /**
     * 转换数据
     *
     * @param Data|array|null $data
     *
     * @return Data|null
     */
    protected function conversionData(Data|array $data = null) : ?Data
    {
        if (is_array($data)) {
            $data = $this->morphsData($data);
            $data = $this->dataWithOwner($data);
        }
        // Data 验证存在问题 如果有两个类型
        return $this->getDataClass()::from($data);
    }

    /**
     * @return string|null|Data
     */
    protected function getDataClass() : ?string
    {
        try {
            $dataClass = (new ReflectionClass($this))->getProperty('data')->getType()->getName();
        } catch (\ReflectionException) {
            $dataClass = $this->service::getDataClass();
        }
        return $dataClass;
    }

    public function setDataClass(?string $dataClass) : static
    {
        $this->dataClass = $dataClass;
        return $this;
    }


    protected function dataWithOwner(array $data) : array
    {
        if ($this->service::$autoModelWithOwner && !isset($data[$this->service::$modelOwnerKey])) {
            if ($this->service->getOwner() instanceof UserData) {
                $data[$this->service::$modelOwnerKey] = $this->service->getOwner()->toArray();
            } elseif ($this->service->getOwner() instanceof UserInterface) {
                $data[$this->service::$modelOwnerKey] = UserData::fromUserInterface($this->service->getOwner())->toArray();
            }
        }
        return $data;
    }

    protected function morphsData(array $data) : array
    {
        if (!method_exists($this->getDataClass(), 'morphs')) {
            return $data;
        }
        $morphs = $this->getDataClass()::morphs();
        foreach ($morphs as $morph) {
            $data = $this->initMorphFromArray($data, $morph);
        }
        return $data;
    }

    protected function initMorphFromArray(array $data, string $morph) : array
    {
        $typeKey     = $morph . '_type';
        $idKey       = $morph . '_id';
        $nicknameKey = $morph . '_nickname';
        $avatarKey   = $morph . '_avatar';
        if (!isset($data[$morph]) && (isset($data[$typeKey]) || isset($data[$idKey]))) {
            $data[$morph] = [
                'id'       => (int)$data[$idKey],
                'type'     => $data[$typeKey],
                'nickname' => $data[$nicknameKey] ?? null,
                'avatar'   => $data[$avatarKey] ?? null,
            ];
        }
        return $data;
    }

}
