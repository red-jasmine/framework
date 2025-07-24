<?php

namespace RedJasmine\PointsMall\Application\Services\PointsProduct\Commands;

use RedJasmine\PointsMall\Application\Services\PointsProduct\PointsProductApplicationService;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Application\HandleContext;


/**
 * @property PointsProductApplicationService $service
 */
class PointsProductCreateCommandHandler extends CreateCommandHandler
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