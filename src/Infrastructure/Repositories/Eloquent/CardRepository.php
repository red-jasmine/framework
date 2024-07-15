<?php

namespace RedJasmine\Card\Infrastructure\Repositories\Eloquent;


use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CardRepository extends EloquentRepository
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = Card::class;

}
