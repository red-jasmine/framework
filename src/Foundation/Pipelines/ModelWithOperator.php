<?php

namespace RedJasmine\Support\Foundation\Pipelines;

use Illuminate\Support\Facades\Auth;
use RedJasmine\Support\Foundation\Service\Actions;

class ModelWithOperator
{


    public function handle(Actions $action, \Closure $closure)
    {

        // TODO 一个管理器
        // 通过设计当前的 守卫
        // 获取 当前的审批人

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
