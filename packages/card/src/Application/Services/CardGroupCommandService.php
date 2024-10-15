<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class CardGroupCommandService extends ApplicationCommandService
{


    protected static string $modelClass = CardGroup::class;


    public function __construct(
        protected CardGroupRepositoryInterface $repository,
    )
    {


    }


}
