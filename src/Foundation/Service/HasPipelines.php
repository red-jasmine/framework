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
     * 所有管道集合
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
     * 管道组合
     * @var Pipelines
     */
    protected Pipelines $pipelines;

    /**
     * @param $passable
     *
     * @return $this
     */
    protected function initPipelines($passable) : static
    {
        $this->pipelines = $this->newPipelines($passable);
        return $this;
    }

    private function newPipelines($passable) : Pipelines
    {
        return app(Pipelines::class)
            ->send($passable)
            ->pipe(static::$commonPipes)
            ->pipe($this->getConfigPipes())
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
        if (blank($this->pipelinesConfigKey)) {
            return [];
        }
        return Config::get($this->pipelinesConfigKey, []);
    }

}
