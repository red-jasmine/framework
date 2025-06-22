<?php

namespace RedJasmine\Distribution\Application\PromoterLevel\Services;

use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\CreatePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\CreatePromoterLevelCommandHandler;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\UpdatePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\UpdatePromoterLevelCommandHandler;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\DeletePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\DeletePromoterLevelCommandHandler;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\PromoterLevelPaginateQuery;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\PromoterLevelPaginateQueryHandler;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\FindPromoterLevelQuery;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\FindPromoterLevelQueryHandler;
use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Distribution\Domain\Transformers\PromoterLevelTransformer;
use RedJasmine\Support\Application\ApplicationService;


class PromoterLevelApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-level.command';

    protected static string $modelClass = PromoterLevel::class;

    protected static $macros = [];

    public function __construct(
        public PromoterLevelRepositoryInterface $repository,
        public PromoterLevelReadRepositoryInterface $readRepository,
        public PromoterLevelTransformer $transformer,
    ) {
    }
}