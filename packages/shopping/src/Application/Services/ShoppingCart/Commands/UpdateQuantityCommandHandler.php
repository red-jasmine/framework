<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class UpdateQuantityCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(UpdateQuantityCommand $command)
    {
        $this->beginDatabaseTransaction();
        try {
            $cart = $this->service->repository->findActiveByUser($command->owner);
            if ($cart) {
                $cart->loadMissing('products');
                $cart->updateQuantity($command->identity, $command->quantity);
                $this->service->repository->store($cart);
            }
            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return $cart;
    }
} 