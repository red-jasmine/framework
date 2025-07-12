<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Queries;

use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Services\ShoppingCartDomainService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindBuyerCartQueryHandler extends QueryHandler
{
    use HasDomainService;

    protected ShoppingCartDomainService $shoppingCartDomainService;

    public function __construct(
        protected ShoppingCartApplicationService $service,
    ) {
        $this->shoppingCartDomainService = $this->makeDomainService(ShoppingCartDomainService::class);
    }

    public function handle(FindBuyerCartQuery $query) : ?ShoppingCart
    {
        $cart = $this->service->readRepository->findByMarketUser($query->buyer, $query->market);
        if ($cart) {
            // 还需要获取商品信息

            $orderAmount = $this->shoppingCartDomainService->show($cart, $query);

            foreach ($cart->products as $product) {
                $productInfo      = $orderAmount->products[$product->id];
                $product->product = $productInfo;
            }
        }


        return $cart ?? $this->service->newModel($query);
    }
} 