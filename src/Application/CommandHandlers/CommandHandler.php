<?php

namespace RedJasmine\Support\Application\CommandHandlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;

class CommandHandler
{
    use CanUseDatabaseTransactions;


    protected static string $modelClass;

    protected Model|null $model = null;


    /**
     * @var mixed
     */
    protected Data|null $command;

    public function __construct(
        protected RepositoryInterface $repository
    ) {
    }

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


    public function setCommand($command) : static
    {
        $this->command = $command;
        return $this;
    }


}
