<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCartApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class RemoveProductCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    /**
     * @param  RemoveProductCommand  $command
     *
     * @return bool
     * @throws Throwable
     */
    public function handle(RemoveProductCommand $command) : bool
    {
        $this->beginDatabaseTransaction();
        try {
            $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);
            if ($cart) {
                $cart->loadMissing('products');
                $this->service->repository->deleteProduct($cart->getProduct($command->getKey()));
            }
            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return true;
    }
}
