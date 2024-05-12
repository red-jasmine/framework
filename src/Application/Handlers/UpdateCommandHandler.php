<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;

class UpdateCommandHandler extends CommandHandler
{

    public function handle(Data $command) : void
    {

        $model           = $this->getService()->getRepository()->find($command->id);
        $this->aggregate = $model;
        if (method_exists($model, 'setOperator')) {
            $model->setOperator($this->getOperator());
        }
        $model->fill($command->toArray());
        $execute = method_exists($model, 'modify') ? fn() => $model->modify() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->update($model),
        );
    }
}
