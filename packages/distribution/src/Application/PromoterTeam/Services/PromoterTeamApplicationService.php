<?php

namespace RedJasmine\Distribution\Application\PromoterTeam\Services;

use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\CreatePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\CreatePromoterTeamCommandHandler;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\UpdatePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\UpdatePromoterTeamCommandHandler;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\DeletePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\DeletePromoterTeamCommandHandler;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\PromoterTeamPaginateQuery;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\PromoterTeamPaginateQueryHandler;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\FindPromoterTeamQuery;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\FindPromoterTeamQueryHandler;
use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Distribution\Domain\Transformers\PromoterTeamTransformer;
use RedJasmine\Support\Application\ApplicationService;


class PromoterTeamApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-team.command';

    protected static string $modelClass = PromoterTeam::class;


    protected static $macros = [];

    public function __construct(
        public PromoterTeamRepositoryInterface $repository,
        public PromoterTeamTransformer $transformer,
    ) {
    }
}
