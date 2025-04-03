<?php

namespace RedJasmine\Interaction\Application\Services;

use RedJasmine\Interaction\Domain\Repositories\InteractionRecordReadRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class InteractionRecordApplicationService extends ApplicationService
{
    public function __construct(
        public InteractionRecordRepositoryInterface $repository,
        public InteractionRecordReadRepositoryInterface $readRepository,
    ) {
    }




}