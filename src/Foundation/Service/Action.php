<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * @property Model   $model
 * @property Service $service
 */
abstract class Action implements ServiceAwareAction
{
    use HasPipelines {
        pipes as corePipes;
    }
    use HasValidatorCombiners {
        validatorCombiners as coreValidatorCombiners;
    }

    use CanUseDatabaseTransactions;

    public function validatorCombiners() : array
    {
        // TODO 合并配置
        return $this->coreValidatorCombiners();
    }


    /**
     * @return Service
     */
    public function getService() : Service
    {
        return $this->service;
    }


    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }

    public function pipes() : array
    {
        return array_merge($this->corePipes(), $this->getConfigPipes());
    }

    protected ?string $actionConfigKey = null;


    protected array $config = [];

    protected function actionConfig()
    {
        //  配置,当前实例
        return [
            'pipelines'           => [], // 操作管道
            'validator_combiners' => [],// 验证组合器
        ];
    }


    /**
     * 管道 配置
     * @var string|null
     */
    protected ?string $pipelinesConfigKey = null;

    /**
     * 获取当前操作配置的管道
     * @return array
     */
    protected function getConfigPipes() : array
    {
        $pipelinesConfigKey = $this->getPipelinesConfigKey();
        if (blank($pipelinesConfigKey)) {
            return [];
        }
        return Config::get($pipelinesConfigKey, []);
    }

    /**
     * 获取 配置的 key
     * @return string|null
     */
    protected function getPipelinesConfigKey() : ?string
    {
        // 从实例中获取
        if (filled($this->pipelinesConfigKey)) {
            $this->pipelinesConfigKey;
        }
        // 服务配置中获取
        if (filled($this->service::$actionPipelinesConfigPrefix)) {
            return $this->service::$actionPipelinesConfigPrefix . '.' . $this->callName;
        }
        return null;
    }


}
