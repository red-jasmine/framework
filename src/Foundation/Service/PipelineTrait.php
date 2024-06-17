<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Support\Facades\Config;

trait PipelineTrait
{


    protected function pipeline()
    {

    }

    // 可以公共的
    // 可以配置的
    // 可以对象内的

    public function pipelines() : array
    {
        return [];
    }

    private array $pipelines = [];

    public function getPipelines() : array
    {
        return $this->pipelines;
    }

    public function setPipelines(array $pipelines) : void
    {
        $this->pipelines = $pipelines;
    }


    public function initializePipelineTrait() : void
    {
        $this->pipelines = array_merge($this->pipelines, $this->getConfigPipelines(), static::$globalPipelines[static::class] ?? []);
    }


    protected ?string $pipelinesConfigKey = null;

    public function getPipelinesConfigKey() : ?string
    {
        return $this->pipelinesConfigKey;
    }

    public function setPipelinesConfigKey(?string $pipelinesConfigKey) : void
    {
        $this->pipelinesConfigKey = $pipelinesConfigKey;
    }


    protected function getConfigPipelines() : array
    {
        if (method_exists($this, 'getPipelinesConfigKey') && $pipelinesConfigKey = $this->getPipelinesConfigKey()) {
            $this->pipelinesConfigKey = $pipelinesConfigKey;
        }
        if ($this->pipelinesConfigKey) {
            return (array)Config::get((string)$this->pipelinesConfigKey, []);
        }
        return [];
    }


    public function addPipeline($pipeline) : static
    {
        if (is_array($pipeline)) {
            array_push($this->pipelines, ...$pipeline);
        } else {
            $this->pipelines[] = $pipeline;
        }

        return $this;
    }


    /**
     * 静态管道
     * @var array
     */
    protected static array $globalPipelines = [];

    public static function extendPipelines($pipelines) : void
    {
        static::$globalPipelines[static::class][] = $pipelines;
    }

    public static function getGlobalPipelines() : array
    {
        return static::$globalPipelines[static::class] ?? [];
    }

    public static function setGlobalPipelines(array $globalPipelines) : void
    {
        static::$globalPipelines[static::class] = $globalPipelines;
    }


}
