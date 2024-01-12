<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Config;

trait HasPipeline
{


    /**
     * 公共 管道
     * @var array
     */
    protected static array $commonPipes = [];
    /**
     * 管道 配置
     * @var string|null
     */
    protected ?string $pipelinesConfigKey = null;
    /**
     * 管道
     * @var array
     */
    protected array $pipes = [];

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

    public function addPipe($pipe) : static
    {
        $this->pipes[] = $pipe;
        return $this;
    }

    protected function pipelines($passable) : Pipeline
    {

        return app(Pipeline::class)->send($passable)
                                   ->pipe(static::$commonPipes)
                                   ->pipe($this->getConfigPipes())
                                   ->pipe($this->pipes);
    }

    protected function getConfigPipes() : array
    {
        if (blank($this->pipelinesConfigKey)) {
            return [];
        }
        return Config::get($this->pipelinesConfigKey, []);
    }

}
