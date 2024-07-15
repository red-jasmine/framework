<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class CardCommandService extends ApplicationCommandService
{
    public function __construct(
        protected CardRepositoryInterface $repository,
    )
    {

        parent::__construct();
    }


}
