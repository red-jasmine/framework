<?php

namespace RedJasmine\Card\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class CardGroupBindProductReadRepository extends QueryBuilderReadRepository implements CardGroupBindProductReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CardGroupBindProduct::class;

}
