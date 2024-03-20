<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;
use phpDocumentor\Reflection\Types\This;
use RedJasmine\Product\Services\Category\Data\ProductSellerCategoryData;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use RedJasmine\Support\Foundation\Pipelines\ModelWithOperator;
use RedJasmine\Support\Foundation\Service\Actions;
use RedJasmine\Support\Foundation\Service\ResourceService;
use ReflectionClass;

/**
 * @property Data|null       $data
 * @property ResourceService $service
 */
abstract class ResourceAction extends Actions
{

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

    public function save() : Model
    {
        try {
            $this->beginDatabaseTransaction();
            $this->resolveModel();
            $this->authorizeAccess();
            // 初始化
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


    protected function getModel() : string
    {
        return $this->service::getModel();
    }

    protected function init($data) : Data
    {
        return $this->conversionData($data);
    }

    /**
     * 验证通过后的数据
     * @return array
     */
    protected function validate() : array
    {
        $data = $this->data->toArray();
        if ($this->getValidator()) {
            return $this->getValidator()->safe()->all();
        }
        return $data;
    }

    protected ?Validator $validator = null;

    public function getValidator()
    {
        if ($this->validator) {
            return $this->validator;
        }
        if ($this->getValidatorManageClass()) {
            $this->validator = app($this->getValidatorManageClass(), [ 'data' => $this->data?->toArray() ?? [] ])->validator();
        }
        return $this->validator;
    }

    protected function getValidatorManageClass() : ?string
    {
        return static::$validatorManageClass ?? $this->service::getValidatorManageClass();

    }

    /**
     * @param array $data
     *
     * @return Model|null
     * @throws Exception
     */
    protected function fill(array $data) : ?Model
    {
        $this->model->fill($data);
        if ($this->model->exists === false && $this->model->incrementing === false) {
            $this->model->{$this->model->getKeyName()} = $this->service::buildID();
        }
        if ($this->service::$modelWithOwner) {
            $this->model->{$this->service::$modelOwnerKey} = $this->data->owner;
        }
        return $this->model;
    }


    protected function after($handleResult) : mixed
    {
        return $handleResult;
    }


    /**
     * 填充数据
     *
     * @param array $data
     *
     * @return void
     */
    protected function fillData(array $data) : void
    {

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

        return $this->getDataClass()::validateAndCreate($data);
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
