<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Queries;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
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

            $orderData     = $this->shoppingCartDomainService->show($cart, $query);
            $orderProducts = collect($orderData->products)->keyBy('shoppingCartId');

            foreach ($cart->products as $product) {
                /**
                 * @var OrderProductData $orderProduct
                 */
                $orderProduct     = $orderProducts[$product->id];
                $product->product = $orderProduct->getProductInfo();
            }
        }


        return $cart ?? $this->service->newModel($query);
    }
} 