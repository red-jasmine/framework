<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCartApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CalculateAmountCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
    ) {
    }

    public function handle(CalculateAmountCommand $command) : ?OrderData
    {
        // 获取购物车
        $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);

        if (!$cart) {
            return null;
        }
        $cart->loadMissing('products');

        return $this->service->calculateCartAmount($cart, $command);
    }
}
