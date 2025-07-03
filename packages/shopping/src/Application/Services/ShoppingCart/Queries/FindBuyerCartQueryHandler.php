<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Queries;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\ProductInfo;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindBuyerCartQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
        protected PromotionServiceInterface $promotionService
    ) {
        $this->shoppingCartDomainService = new ShoppingCartDomainService(
            $this->productService,
            $this->stockService,
            $this->promotionService,
        );
    }

    public function handle(FindBuyerCartQuery $query) : ?ShoppingCart
    {
        $cart = $this->service->readRepository->findByMarketUser($query->buyer, $query->market);
        if ($cart) {
            // 还需要获取商品信息
            foreach ($cart->products as $product) {
                $product->selected = true;
            }
            $orderAmount = $this->shoppingCartDomainService->getOrderAmount($cart, $query);

            foreach ($cart->products as $product) {

                $productInfo = $orderAmount->products[$product->id];

                $product->product = $productInfo;
            }
        }


        return $cart ?? $this->service->newModel($query);
    }
} 