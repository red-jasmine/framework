<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder;

use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommandHandler;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Domain\Services\PointsExchangeService;
use RedJasmine\PointsMall\Domain\Transformers\PointsExchangeOrderTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see PointsExchangeOrderCreateCommandHandler::handle()
 * @method PointsExchangeOrder create(PointsExchangeOrderCreateCommand $command)
 */
class PointsExchangeOrderApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'points-mall.product.application';
    protected static string $modelClass     = PointsExchangeOrder::class;


    public function __construct(
        public PointsExchangeOrderRepositoryInterface $repository,
        public PointsExchangeOrderReadRepositoryInterface $readRepository,
        public PointsExchangeService $pointsExchangeService,
        public PointsProductRepositoryInterface $pointsProductRepository,
        public PointsExchangeOrderTransformer $transformer,

    ) {
    }

    protected static $macros = [
        'create' => PointsExchangeOrderCreateCommandHandler::class,
    ];
}