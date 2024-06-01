<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Support\Facades\Config;

trait PipelineTrait
{

    protected array $pipelines = [];

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
        return static::$globalPipelines[static::class];
    }

    public static function setGlobalPipelines(array $globalPipelines) : void
    {
        static::$globalPipelines[static::class] = $globalPipelines;
    }


    /**
     * 管道组合
     * @var Pipeline
     */
    protected Pipeline $pipelineManager;

    protected function pipelineManager() : Pipeline
    {
        return $this->pipelineManager = $this->pipelineManager ?? $this->newPipelineManager($this);
    }

    protected function newPipelineManager($passable) : Pipeline
    {
        return app(Pipeline::class)->send($passable)->pipe($this->pipelines);
    }


}
