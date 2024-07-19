<?php

namespace RedJasmine\Card\Domain\Repositories;

use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface CardGroupBindProductRepositoryInterface extends RepositoryInterface
{

    public function findByProduct(UserInterface $owner, string $productType, int $productId, int $skuId) : ?CardGroupBindProduct;


}
