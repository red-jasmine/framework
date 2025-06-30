<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindCartQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(FindCartQuery $query): ?ShoppingCart
    {
        $cart = $this->service->readRepository->findActiveByUser($query->owner);
        if ($cart) {
            $cart->load('products');
        }
        return $cart;
    }
} 