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


    protected function handle(Closure $execute, ?Closure $persistence = null) : void
    {

        $this->pipelineManager()->call('executing');

        $this->pipelineManager()->call('execute', $execute);
        // 持久化
        if ($persistence) {
            $persistence();
        }
        $this->pipelineManager()->call('executed');
    }


    protected array $executeArgs = [];


    public function getExecuteArgs() : array
    {
        return $this->executeArgs;
    }

    public function setExecuteArgs(array $executeArgs) : static
    {
        $this->executeArgs = $executeArgs;
        return $this;
    }


    protected ?string $pipelinesConfigKeyPrefix = 'pipelines';

    public function getPipelinesConfigKey() : string
    {
        return Str::finish($this->pipelinesConfigKeyPrefix, '.') . Str::remove('CommandHandler', class_basename(static::class));
    }


}
