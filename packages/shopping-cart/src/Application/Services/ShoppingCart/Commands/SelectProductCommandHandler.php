<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class SelectProductCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(SelectProductCommand $command) : bool
    {
        $this->beginDatabaseTransaction();
        try {
            $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);
            if ($cart) {
                $cart->load('products');
                $cart->selectProduct($command->getKey(), $command->selected);
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


