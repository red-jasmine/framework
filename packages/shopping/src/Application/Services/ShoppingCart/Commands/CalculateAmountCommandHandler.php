<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderAmountInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CalculateAmountCommandHandler extends CommandHandler
{

    use HasDomainService;
    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,

    ) {
        $this->shoppingCartDomainService = $this->makeDomainService(ShoppingCartDomainService::class);
    }

    public function handle(CalculateAmountCommand $command) : ?OrderData
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