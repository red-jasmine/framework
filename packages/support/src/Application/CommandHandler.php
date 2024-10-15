<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;
use RedJasmine\Support\Foundation\Service\ServiceMacro;


/**
 *
 * @property                           $model
 * @property ApplicationCommandService $service
 * @method  ApplicationCommandService getService()
 */
abstract class CommandHandler extends ServiceMacro
{

    use CanUseDatabaseTransactions;

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

    }


}
