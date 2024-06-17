<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Facades\ServiceContext;

class UpdateCommandHandler extends CommandHandler
{

    /**
     * @param Data $command
     *
     * @return void
     */
    public function handle(Data $command) : void
    {
        $this->model = $this->getService()->getRepository()->find($command->id);

        $this->model->fill($command->toArray());

        if ($this->model instanceof OperatorInterface) {
            $this->model->updater = ServiceContext::getOperator();
        }

        $execute = method_exists($this->model, 'modify') ? fn() => $this->model->modify() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->update($this->model),
        );
    }
}
