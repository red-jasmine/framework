<?php

namespace RedJasmine\Distribution\Application\PromoterApply\Services;

use RedJasmine\Distribution\Application\PromoterApply\Services\Commands\PromoterApplyApprovalCommand;
use RedJasmine\Distribution\Application\PromoterApply\Services\Commands\PromoterApplyApprovalCommandHandler;
use RedJasmine\Distribution\Application\PromoterApply\Services\Queries\PromoterApplyPaginateQueryHandler;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Distribution\Domain\Transformers\PromoterApplyTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see PromoterApplyApprovalCommandHandler::handle()
 * @method approval(PromoterApplyApprovalCommand $command)
 */
class PromoterApplyApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-apply.command';

    protected static string $modelClass = PromoterApply::class;

    protected static $macros = [
        'approval' => PromoterApplyApprovalCommandHandler::class,
        'paginate' => PromoterApplyPaginateQueryHandler::class,
    ];

    public function __construct(
        public PromoterApplyRepositoryInterface $repository,
        public PromoterApplyReadRepositoryInterface $readRepository,
        public PromoterApplyTransformer $transformer,
    ) {
    }
}
