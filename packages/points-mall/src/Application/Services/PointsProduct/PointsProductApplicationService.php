<?php

namespace RedJasmine\PointsMall\Application\Services\PointsProduct;

use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductCreateCommandHandler;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductUpdateCommandHandler;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Domain\Services\PointsProductService;
use RedJasmine\PointsMall\Domain\Transformers\PointsProductTransformer;
use RedJasmine\Support\Application\ApplicationService;

class PointsProductApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'points-mall.product.application';
    protected static string $modelClass     = PointsProduct::class;

    public function __construct(
        public PointsProductRepositoryInterface $repository,
        public PointsProductReadRepositoryInterface $readRepository,
        public PointsProductTransformer $transformer,
        public PointsProductService $productService,

    ) {
    }

    protected static $macros = [
        'create' => PointsProductCreateCommandHandler::class,
        'update' => PointsProductUpdateCommandHandler::class,
    ];
}