<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Support\Str;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Foundation\Service\BootTrait;
use RedJasmine\Support\Foundation\Service\MacroAwareService;
use RedJasmine\Support\Foundation\Service\PipelineTrait;


/**
 * @property                           $aggregate
 * @property                           $model
 * @property ApplicationCommandService $service
 * @method  ApplicationCommandService getService()
 */
abstract class CommandHandler implements CommandHandlerInterface, MacroAwareService
{


    /**
     * @var mixed
     */
    protected mixed $command;

    public function setCommand($command) : static
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel() : mixed
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     *
     * @return CommandHandler
     */
    public function setModel(mixed $model) : static
    {
        $this->model = $model;
        return $this;
    }


    use BootTrait;

    use AwareServiceHelper;


    use PipelineTrait;


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

        // 需要进行改造 TODO
        $result = $this->pipelineManager()->call('executing');

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
