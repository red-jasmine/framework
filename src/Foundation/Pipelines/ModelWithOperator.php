<?php

namespace RedJasmine\Support\Foundation\Pipelines;


use RedJasmine\Support\Foundation\Service\Actions;

class ModelWithOperator
{

    public function handle(Actions $action, \Closure $closure)
    {
        if ($action->model->hasOperator()) {
            if ($action->model->exists) {
                $action->model->updater = $action->service->getOperator();
            } else {
                $action->model->creator = $action->service->getOperator();
            }
        }
        return $closure($action);
    }

}
