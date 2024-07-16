<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class CardQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardReadRepositoryInterface $repository,
    )
    {
        parent::__construct();
    }


}
