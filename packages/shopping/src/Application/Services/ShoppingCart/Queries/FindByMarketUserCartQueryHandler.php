<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Queries;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindByMarketUserCartQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
        protected ProductServiceInterface $productService,
    ) {
    }

    public function handle(FindByMarketUserCartQuery $query) : ?ShoppingCart
    {
        $cart = $this->service->readRepository->findByMarketUser($query->owner, $query->market);

        foreach ($cart->products as $product) {
            $factors           = new ProductPurchaseFactor();
            $factors->product  = $product->getProduct();
            $factors->market   = $cart->market;
            $factors->quantity = $product->quantity;
            $factors->buyer    = $cart->owner;
            $productInfo       = $this->productService->getProductInfo($factors);

            if ($productInfo->isAvailable) {
                $productAmount          = $this->productService->getProductAmount($factors);

                $product->price = $productAmount->price;
            }
            $product->productInfo = $productInfo;
        }


        return $cart ?? $this->service->newModel($query);
    }
} 