<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class CardGroupApplicationService extends ApplicationService
{


    protected static string $modelClass = CardGroup::class;


    public function __construct(
        public CardGroupRepositoryInterface $repository,
        public CardGroupReadRepositoryInterface $readRepository,
    ) {


    }


}
