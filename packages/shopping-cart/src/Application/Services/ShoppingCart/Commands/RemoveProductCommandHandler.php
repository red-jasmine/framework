<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class RemoveProductCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(RemoveProductCommand $command): bool
    {
        $this->beginDatabaseTransaction();
        try {
            $cart = $this->service->repository->findActiveByUser($command->owner);
            if ($cart) {
                $cart->loadMissing('products');
                $cart->removeProduct($command->identity);
                $this->service->repository->store($cart);
            }
            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return true;
    }
} 