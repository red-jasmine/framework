<?php

namespace RedJasmine\Support\Application\CommandHandlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;

abstract class CommandHandler
{

    use HasHooks;


    use CanUseDatabaseTransactions;


    protected Model|null $model = null;


    /**
     * @var mixed
     */
    protected Data|null $command;


    /**
     * @return \Model|null
     */
    public function getModel() : ?\Model
    {
        return $this->model;
    }

    /**
     * @param  Model  $model
     *
     * @return static
     */
    public function setModel(Model $model) : static
    {
        $this->model = $model;
        return $this;
    }

    public function getCommand() : ?Data
    {
        return $this->command;
    }

    public function setCommand($command) : static
    {
        $this->command = $command;
        return $this;
    }

    public function getRepository() : RepositoryInterface
    {
        if (property_exists($this, 'repository')) {
            return $this->repository;
        } else {
            return $this->getService()->getRepository();
        }
    }


}
