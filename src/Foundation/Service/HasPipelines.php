<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Support\Facades\Config;

/**
 * 具有管道
 */
trait HasPipelines
{


    protected function initializeHasPipelines() : void
    {
        $this->pipes = array_merge($this->pipes, static::$globalPipes, $this->pipes());
    }

    /**
     * 静态管道
     * @var array
     */
    protected static array $globalPipes = [];


    public static function extendPipes($pipe) : void
    {
        static::$globalPipes[] = $pipe;
    }

    public static function getGlobalPipes() : array
    {
        return self::$globalPipes;
    }

    public static function setGlobalPipes(array $globalPipes) : void
    {
        self::$globalPipes = $globalPipes;
    }

    /**
     * 实例配置
     * @var array
     */
    protected array $pipes = [];


    public function addPipe($pipe) : static
    {
        if (!is_array($pipe)) {
            $pipe = [ $pipe ];
        }
        array_push($this->pipes, ...$pipe);

        return $this;
    }

    public function getPipes() : array
    {
        return $this->pipes;
    }

    public function setPipes(array $pipes) : static
    {
        $this->pipes = $pipes;
        return $this;
    }


    /**
     * 实例配置的
     * @return array
     */
    public function pipes() : array
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

    protected function newPipelines($passable) : Pipelines
    {
        return app(Pipelines::class)->send($passable)->pipe($this->pipes);
    }


}
