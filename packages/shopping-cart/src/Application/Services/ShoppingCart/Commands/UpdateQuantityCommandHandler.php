<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCartApplicationService;
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
            $cart = $this->service->repository->findActiveByUser($command->buyer,$command->market);
            if ($cart) {
                $cart->loadMissing('products');

                $cart->updateQuantity($command->getKey(), $command->quantity);

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
