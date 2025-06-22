<?php

namespace RedJasmine\Distribution\Application\Promoter\Services;

use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterRegisterCommand;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterRegisterCommandHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterAuditCommandHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterDowngradeCommandHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterSetParentCommandHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterUpgradeCommand;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterUpgradeCommandHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQueryHandler;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindPromoterByIdQueryHandler;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see PromoterRegisterCommandHandler::handle()
 * @method register(PromoterRegisterCommand $command)
 * @see PromoterUpgradeCommandHandler::handle()
 * @method upgrade(PromoterUpgradeCommand $command)
 * @method findPromoterById(FindPromoterByIdQuery $query)
 * @method findByOwner(FindByOwnerQuery $query)
 */
class PromoterApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter.command';

    protected static string $modelClass = Promoter::class;

    protected static $macros = [
        'register'         => PromoterRegisterCommandHandler::class,
        'upgrade'          => PromoterUpgradeCommandHandler::class,
        'downgrade'        => PromoterDowngradeCommandHandler::class,
        'setParent'        => PromoterSetParentCommandHandler::class,
        'findPromoterById' => FindPromoterByIdQueryHandler::class,
        'findByOwner'      => FindByOwnerQueryHandler::class
    ];

    public function __construct(
        public PromoterRepositoryInterface $repository,
        public PromoterReadRepositoryInterface $readRepository,
        public PromoterLevelReadRepositoryInterface $levelReadRepository,
    ) {
    }
}
