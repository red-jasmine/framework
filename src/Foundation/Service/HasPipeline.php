<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Config;

trait HasPipeline
{

    private array $pipelines = [];

    protected bool $loadPipelines = false;

    /**
     * 管道 配置
     * @var string|null
     */
    protected ?string $pipelinesConfigKey = null;

    public function addPipeline($pipeline) : static
    {
        $this->pipelines[] = $pipeline;
        return $this;
    }

    public function loadConfigPipelines() : static
    {
        if ($this->loadPipelines === false && $this->pipelinesConfigKey) {
            $this->pipelines = array_merge($this->pipelines, $this->getConfigPipelines());
        }
        return $this;
    }

    protected function getConfigPipelines() : array
    {
        return Config::get($this->pipelinesConfigKey, []);
    }

    public function getPipelines() : array
    {
        $this->loadConfigPipelines();
        return $this->pipelines;
    }

    protected function pipelines($passable, ?callable $destination = null)
    {
        if ($destination === null) {
            $destination = function ($passable) {
                return $passable;
            };
        }
        return app(Pipeline::class)
            ->send($passable)
            ->through($this->getPipelines())
            ->then($destination);
    }

}
