<?php

namespace RedJasmine\Interaction\Application\Services;

use RedJasmine\Interaction\Application\Services\Commands\InteractionCreateCommandHandler;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordReadRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticReadRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class InteractionApplicationService extends ApplicationService
{

    public function __construct(
        public InteractionStatisticRepositoryInterface $repository,
        public InteractionStatisticReadRepositoryInterface $readRepository,
        public InteractionRecordRepositoryInterface $recordRepository,
        public InteractionRecordReadRepositoryInterface $recordReadRepository,

    ) {
    }

    // 删除互动

    protected static $macros = [
        'create' => InteractionCreateCommandHandler::class
    ];


}