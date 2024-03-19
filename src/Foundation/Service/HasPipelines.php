<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Support\Facades\Config;

/**
 * 具有管道
 */
trait HasPipelines
{

    /**
     * 公共 管道
     * @var array
     */
    protected static array $commonPipes = [];

    /**
     * 扩展公共管道
     *
     * @param $pipe
     *
     * @return void
     */
    public static function extendPipes($pipe) : void
    {
        static::$commonPipes[] = $pipe;
    }


    /**
     * 实例配置
     * @var array
     */
    private array $pipes = [];

    /**
     * 对实例添加管道
     *
     * @param $pipe
     *
     * @return $this
     */
    public function addPipe($pipe) : static
    {
        $this->pipes[] = $pipe;
        return $this;
    }


    /**
     * 实例配置的
     * @return array
     */
    protected function pipes() : array
    {
        return [];
    }


    /**
     * 管道组合
     * @var Pipelines
     */
    protected Pipelines $pipelines;

    protected function getPipelines() : Pipelines
    {
        return $this->pipelines = $this->pipelines ?? $this->newPipelines($this);
    }

    private function newPipelines($passable) : Pipelines
    {
        return app(Pipelines::class)
            ->send($passable)
            ->pipe(static::$commonPipes)
            ->pipe($this->getConfigPipes())
            ->pipe($this->pipes())
            ->pipe($this->pipes);
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
