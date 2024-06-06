<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Foundation\Service\AwareArgumentsAble;
use RedJasmine\Support\Foundation\Service\AwareServiceAble;
use RedJasmine\Support\Foundation\Service\MacroAwareArguments;
use RedJasmine\Support\Foundation\Service\MacroAwareService;


/**
 *
 * @property                           $model
 * @property ApplicationCommandService $service
 * @method  ApplicationCommandService getService()
 */
abstract class CommandHandler implements MacroAwareService, MacroAwareArguments
{


    use AwareServiceAble;


    use AwareArgumentsAble;


    /**
     * @var mixed
     */
    protected mixed $command;

    public function setCommand($command) : static
    {
        $this->command = $command;
        return $this;
    }

    protected Model|null $model = null;

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


    /**
     * @param Closure|null $execute
     * @param Closure|null $persistence
     *
     * @return mixed
     */
    protected function execute(?Closure $execute = null, ?Closure $persistence = null) : mixed
    {

        return $persistence();
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
