<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CalculateAmountCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
        protected ShoppingCartDomainService $shoppingCartDomainService
    ) {
    }

    public function handle(CalculateAmountCommand $command) : ?OrderData
    {
        $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);
        if (!$cart) {
            return null;
        }
        $cart->loadMissing('products');
        return $this->shoppingCartDomainService->calculates($cart, $command);
    }
}


