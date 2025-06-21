<?php

namespace RedJasmine\Distribution\Application\PromoterApply\Services;

use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class PromoterApplyApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-apply.command';

    protected static string $modelClass = PromoterApply::class;

    protected static $macros = [];

    public function __construct(
        public PromoterApplyRepositoryInterface $repository,
        public PromoterApplyReadRepositoryInterface $readRepository,

    ) {
    }
}
