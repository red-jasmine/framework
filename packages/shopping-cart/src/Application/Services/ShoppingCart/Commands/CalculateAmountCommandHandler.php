<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class CalculateAmountCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(CalculateAmountCommand $command): ShoppingCart
    {
        $cart = $this->service->repository->findActiveByUser($command->owner);
        if ($cart) {
            $cart->loadMissing('products');
            $cart->calculateAmount();
            $this->service->repository->store($cart);
        }
        return $cart;
    }
} 