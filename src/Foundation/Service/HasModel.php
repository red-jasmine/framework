<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $model
 */
trait HasModel
{


    /**
     * 资源模型类
     * @var string|null
     */
    protected ?string $modelClass = null;

    protected ?Model $model = null;

    protected int|string|null $key = null;

    public function getModelClass() : ?string
    {
        return $this->modelClass;
    }

    public function setModelClass(?string $modelClass) : static
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    public function getModel() : ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model) : static
    {
        $this->model = $model;
        return $this;
    }

    public function getKey() : int|string|null
    {
        return $this->key;
    }

    public function setKey(int|string|null $key) : static
    {
        $this->key = $key;
        return $this;
    }


}
