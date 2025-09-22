<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\Enums\ShoppingCartStatusEnum;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class AddProductCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
    ) {
    }

    /**
     * @param  AddProductCommand  $command
     *
     * @return ShoppingCart
     * @throws Throwable
     */
    public function handle(AddProductCommand $command) : ShoppingCart
    {
        $this->beginDatabaseTransaction();
        try {

            // 1. 查找或创建购物车
            $cart = $this->service->repository
                        ->findActiveByUser($command->buyer, $command->market)
                    ?? ShoppingCart::make([
                    'owner'  => $command->buyer,
                    'market' => $command->market,
                    'status' => ShoppingCartStatusEnum::ACTIVE,
                ]);
            $cart->products;

            // 2. 添加商品到购物车
            $this->service->addProductToCart($cart, $command);

            $this->service->repository->store($cart);

            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return $cart;
    }
}
