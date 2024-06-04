<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Facades\ServiceContext;

class CreateCommandHandler extends CommandHandler
{


    public function handle(Data $command) : mixed
    {
        /**
         * @var $model Model
         */
        $model           = $this->getService()->newModel($command);
        $this->aggregate = $model;
        $model->fill($command->toArray());
        foreach ($command::morphs() as $key) {
            $model->{$key} = $command->{$key};
        }
        if (method_exists($model, 'creator')) {
            $model->creator = ServiceContext::getOperator();
        }
        $execute = method_exists($model, 'create') ? fn() => $model->create() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->store($model),
        );

        return $model;
    }
}
