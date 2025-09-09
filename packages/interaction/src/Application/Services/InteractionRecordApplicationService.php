<?php

namespace RedJasmine\Interaction\Application\Services;

use Illuminate\Support\Collection;
use RedJasmine\Interaction\Application\Services\Commands\InteractionCancelCommand;
use RedJasmine\Interaction\Application\Services\Commands\InteractionRecordCancelCommandHandler;
use RedJasmine\Interaction\Application\Services\Commands\InteractionRecordCreateCommandHandler;
use RedJasmine\Interaction\Application\Services\Queries\StatisticQuery;
use RedJasmine\Interaction\Application\Services\Queries\StatisticQueryHandler;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method Collection statistic(StatisticQuery $query)
 * @method boolean cancel(InteractionCancelCommand $command)
 */
class InteractionRecordApplicationService extends ApplicationService
{
    public function __construct(
        public InteractionRecordRepositoryInterface $repository,
        public InteractionStatisticRepositoryInterface $statisticRepository,
    ) {
    }

    protected static $macros = [
        'create'    => InteractionRecordCreateCommandHandler::class,
        'cancel'    => InteractionRecordCancelCommandHandler::class,
        'update'    => null,
        'delete'    => null,
        'statistic' => StatisticQueryHandler::class
    ];


}
