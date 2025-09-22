<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindBuyerCartQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
    ) {
    }

    public function handle(FindBuyerCartQuery $query) : ?ShoppingCart
    {
        $cart = $this->service->repository->findByMarketUser($query->buyer, $query->market);
        if ($cart) {
            // 还需要获取商品信息
            $orderData     = $this->service->calculateCartAmount($cart, $query, false);
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
