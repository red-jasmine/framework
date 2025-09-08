<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * @property CardGroupBindProduct $modelClass
 */
class CardGroupBindProductRepository extends Repository implements CardGroupBindProductRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CardGroupBindProduct::class;


    public function findByProduct(UserInterface $owner, string $productType, int $productId, int $skuId) : ?CardGroupBindProduct
    {
        return static::$modelClass::query()->onlyOwner($owner)
                                          ->where('product_type', $productType)
                                          ->where('product_id', $productId)
                                          ->where('sku_id', $skuId)
                                          ->first();

    }


}
