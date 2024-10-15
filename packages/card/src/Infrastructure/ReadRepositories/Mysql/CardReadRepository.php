<?php

namespace RedJasmine\Card\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class CardReadRepository extends QueryBuilderReadRepository implements CardReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Card::class;

}
