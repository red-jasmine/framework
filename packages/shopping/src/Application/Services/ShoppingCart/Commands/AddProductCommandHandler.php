<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Models\Enums\ShoppingCartStatusEnum;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class AddProductCommandHandler extends CommandHandler
{
    use HasDomainService;

    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,
    ) {
        $this->shoppingCartDomainService =  $this->makeDomainService(ShoppingCartDomainService::class);
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