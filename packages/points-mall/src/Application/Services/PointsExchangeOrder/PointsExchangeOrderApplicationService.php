<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder;

use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommandHandler;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPaidCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPaidCommandHandler;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPayCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPayCommandHandler;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Domain\Services\PointsExchangeService;
use RedJasmine\PointsMall\Domain\Transformers\PointsExchangeOrderTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see PointsExchangeOrderCreateCommandHandler::handle()
 * @method PointsExchangeOrder create(PointsExchangeOrderCreateCommand $command)
 * @see PointsExchangeOrderPayCommandHandler::handle()
 * @method PaymentTradeResult pay(PointsExchangeOrderPayCommand $command)
 * @see PointsExchangeOrderPaidCommandHandler::handle()
 * @method bool paid(PointsExchangeOrderPaidCommand $command)
 */
class PointsExchangeOrderApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'points-mall.product.application';
    protected static string $modelClass     = PointsExchangeOrder::class;


    public function __construct(
        public PointsExchangeOrderRepositoryInterface $repository,
        public PointsExchangeService $pointsExchangeService,
        public PointsProductRepositoryInterface $pointsProductRepository,
        public PointsExchangeOrderTransformer $transformer,
    ) {
    }

    protected static $macros = [
        'create' => PointsExchangeOrderCreateCommandHandler::class,
        'pay'    => PointsExchangeOrderPayCommandHandler::class,
        'paid'   => PointsExchangeOrderPaidCommandHandler::class
    ];
}