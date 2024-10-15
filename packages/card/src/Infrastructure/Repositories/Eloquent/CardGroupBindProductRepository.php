<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * @property CardGroupBindProduct $eloquentModelClass
 */
class CardGroupBindProductRepository extends EloquentRepository implements CardGroupBindProductRepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = CardGroupBindProduct::class;


    public function findByProduct(UserInterface $owner, string $productType, int $productId, int $skuId) : ?CardGroupBindProduct
    {
        return static::$eloquentModelClass::query()->onlyOwner($owner)
                                          ->where('product_type', $productType)
                                          ->where('product_id', $productId)
                                          ->where('sku_id', $skuId)
                                          ->first();

    }


}
