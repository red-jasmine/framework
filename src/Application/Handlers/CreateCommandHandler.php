<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;

class CreateCommandHandler extends CommandHandler
{


    public function handle(Data $data) : mixed
    {
        /**
         * @var $model Model
         */
        $model           = $this->getService()->newModel();
        $this->aggregate = $model;
        if (method_exists($model, 'setOperator')) {
            $model->setOperator($this->getOperator());
        }
        $model->fill($data->toArray());

        $execute = method_exists($model, 'create') ? fn() => $model->create() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->store($model),
        );

        return $model->getKey();
    }
}
