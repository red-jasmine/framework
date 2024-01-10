<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Config;

class Action implements ServiceAwareAction
{
    public function setService(Service $service) : static
    {
        $this->service = $service;
        return $this;
    }

    private array $pipelines = [];

    protected bool $loadPipelines = false;

    protected ?string $pipelinesKey = null;

    public function addPipeline($pipeline) : static
    {
        $this->pipelines[] = $pipeline;
        return $this;
    }

    public function loadConfigPipelines() : static
    {
        if ($this->loadPipelines === false && $this->pipelinesKey) {
            $this->pipelines = array_merge($this->pipelines, $this->getConfigPipelines());
        }
        return $this;
    }

    protected function getConfigPipelines() : array
    {
        return Config::get($this->pipelinesKey, []);
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
