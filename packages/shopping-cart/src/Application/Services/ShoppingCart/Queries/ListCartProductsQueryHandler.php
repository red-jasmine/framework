<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\Application\Queries\QueryHandler;
use Illuminate\Database\Eloquent\Collection;

class ListCartProductsQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(ListCartProductsQuery $query): Collection
    {
        if ($query->cartId) {
            return $this->service->readRepository->findProductsByCart($query->cartId);
        }
        $cart = $this->service->readRepository->findActiveByUser($query->owner);
        if ($cart) {
            $cart->load('products');
            return $cart->products;
        }
        return collect();
    }
} 