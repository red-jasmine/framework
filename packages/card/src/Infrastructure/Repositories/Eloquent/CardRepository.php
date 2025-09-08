<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CardRepository extends Repository implements CardRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Card::class;

}
