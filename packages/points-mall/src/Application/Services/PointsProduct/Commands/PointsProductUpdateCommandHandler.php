<?php

namespace RedJasmine\PointsMall\Application\Services\PointsProduct\Commands;

use RedJasmine\PointsMall\Application\Services\PointsProduct\PointsProductApplicationService;
use RedJasmine\Support\Application\Commands\UpdateCommandHandler;
use RedJasmine\Support\Application\HandleContext;


/**
 * @property PointsProductApplicationService $service
 */
class PointsProductUpdateCommandHandler extends UpdateCommandHandler
{
    /**
     * 填充转换数据
     *
     * @param  HandleContext  $context
     *
     * @return void
     */
    protected function fill(HandleContext $context) : void
    {
        parent::fill($context);

        $this->service->productService->validate($context->getModel());


    }


}