<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Support\Str;
use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Foundation\Service\BootTrait;
use RedJasmine\Support\Foundation\Service\PipelineTrait;


/**
 * @property $aggregate
 * @property $service
 */
abstract class CommandHandler implements CommandHandlerInterface
{

    use BootTrait;

    use WithService;

    /**
     * 如何进行可配置化
     */
    use PipelineTrait;

    use HasServiceContext;


    /**
     * @return mixed
     */
    public function getAggregate() : mixed
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
     * @param Closure|null $execute
     * @param Closure|null $persistence
     *
     * @return mixed
     */
    protected function execute(?Closure $execute = null, ?Closure $persistence = null) : mixed
    {

        $this->pipelineManager()->call('executing');

        $execute = $execute ?: function () {
        };

        $result = $this->pipelineManager()->call('execute', $execute);
        // 持久化
        $persistence ? $persistence() : null;
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


    protected ?string $pipelinesConfigKeyPrefix = null;

    public function setPipelinesConfigKeyPrefix(?string $pipelinesConfigKeyPrefix) : void
    {
        $this->pipelinesConfigKeyPrefix = $pipelinesConfigKeyPrefix;
    }


    public function getPipelinesConfigKey() : ?string
    {
        if (blank($this->pipelinesConfigKeyPrefix)) {
            return null;
        }
        $pipelinesConfigKeyPrefix = $this->pipelinesConfigKeyPrefix;
        return Str::finish($pipelinesConfigKeyPrefix, '.') . Str::lower(Str::remove('CommandHandler', class_basename(static::class)));
    }


}
