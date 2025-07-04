<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use Cknow\Money\Money;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CalculateAmountCommandHandler extends CommandHandler
{

    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,

    ) {
        $this->shoppingCartDomainService = new ShoppingCartDomainService(
            app(ProductServiceInterface::class),
            app(StockServiceInterface::class),
            app(PromotionServiceInterface::class),
            app(OrderServiceInterface::class),
        );
    }

    public function handle(CalculateAmountCommand $command) : ?OrderAmountData
    {

        // 获取购物车
        // 获取购物车中的商品
        $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);


        if (!$cart) {
            return null;
        }
        $cart->loadMissing('products');

        return $this->shoppingCartDomainService->calculates($cart, $command);


    }
} 