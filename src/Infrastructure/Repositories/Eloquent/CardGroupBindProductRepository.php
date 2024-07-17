<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CardGroupBindProductRepository extends EloquentRepository implements CardGroupBindProductRepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = CardGroupBindProduct::class;

}
