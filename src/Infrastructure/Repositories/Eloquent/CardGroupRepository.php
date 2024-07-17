<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CardGroupRepository extends EloquentRepository implements CardGroupRepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = CardGroup::class;

}
