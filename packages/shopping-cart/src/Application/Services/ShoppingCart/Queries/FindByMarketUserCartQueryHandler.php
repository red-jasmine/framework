<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindByMarketUserCartQueryHandler extends QueryHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function handle(FindByMarketUserCartQuery $query) : ?ShoppingCart
    {
        $cart = $this->service->readRepository->findByMarketUser($query->owner, $query->market);

        return $cart ?? $this->service->newModel($query);
    }
} 