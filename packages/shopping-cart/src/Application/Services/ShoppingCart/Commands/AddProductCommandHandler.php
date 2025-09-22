<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\Enums\ShoppingCartStatusEnum;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class AddProductCommandHandler extends CommandHandler
{
    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,
        ShoppingCartDomainService $shoppingCartDomainService
    ) {
        $this->shoppingCartDomainService = $shoppingCartDomainService;
    }

    public function handle(AddProductCommand $command) : ShoppingCart
    {
        $this->beginDatabaseTransaction();
        try {
            $cart = $this->service->repository
                        ->findActiveByUser($command->buyer, $command->market)
                    ?? ShoppingCart::make([
                        'owner'  => $command->buyer,
                        'market' => $command->market,
                        'status' => ShoppingCartStatusEnum::ACTIVE,
                    ]);
            $cart->products;

            $this->shoppingCartDomainService->addProduct($cart, $command);
            $this->service->repository->store($cart);
            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return $cart;
    }
}


