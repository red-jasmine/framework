<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactors;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Contracts\StockServiceInterface;
use RedJasmine\ShoppingCart\Domain\Data\CartStockInfo;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class CalculateAmountCommandHandler extends CommandHandler
{

    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
    ) {
        $this->shoppingCartDomainService = new ShoppingCartDomainService(
            $this->productService,
            $this->stockService
        );
    }

    public function handle(CalculateAmountCommand $command) : ?Money
    {

        // 获取购物车
        // 获取购物车中的商品
        $cart = $this->service->repository->findActiveByUser($command->buyer, $command->market);


        if (!$cart) {
            return null;
        }
        $cart->loadMissing('products');

        foreach ($cart->products as $product) {
            $product->selected = true;
        }

        // TODO 验证数量是否一致
        //$selectProducts = $cart->products->whereIn('id', $command->cartProducts)->all();

        $this->shoppingCartDomainService->calculates($cart, $command);

        dd($selectProducts);




        if ($cart) {
            $cart->loadMissing('products');
            $cart->calculateAmount();

        }
        return $cart;
    }
} 