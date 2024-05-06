<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Support\Str;
use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Foundation\Service\BootTrait;
use RedJasmine\Support\Foundation\Service\PipelineTrait;


/**
 * @property $aggregate
 */
abstract class CommandHandler implements CommandHandlerInterface
{

    use BootTrait;

    /**
     * 如何进行可配置化
     */
    use PipelineTrait;

    use HasServiceContext;


    /**
     * @return mixed
     */
    public function getAggregate()
    {
        return $this->aggregate;
    }

    /**
     * @param mixed $aggregate
     *
     * @return CommandHandler
     */
    public function setAggregate(mixed $aggregate) : static
    {
        $this->aggregate = $aggregate;
        return $this;
    }


    /**
     * @param Closure      $execute
     * @param Closure|null $persistence
     *
     * @return mixed
     */
    protected function handle(Closure $execute, ?Closure $persistence = null) : mixed
    {

        $this->pipelineManager()->call('executing');

        $result = $this->pipelineManager()->call('execute', $execute);
        // 持久化
        if ($persistence) {
            $persistence();
        }
        $this->pipelineManager()->call('executed');

        return $result;
    }


    protected array $arguments = [];

    public function getArguments() : array
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments) : static
    {
        $this->arguments = $arguments;
        return $this;
    }






    protected ?string $pipelinesConfigKeyPrefix = 'pipelines';

    public function getPipelinesConfigKey() : string
    {
        return Str::finish($this->pipelinesConfigKeyPrefix, '.') . Str::remove('CommandHandler', class_basename(static::class));
    }


}
