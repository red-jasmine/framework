<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CardGroupRepository extends Repository implements CardGroupRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CardGroup::class;

}
