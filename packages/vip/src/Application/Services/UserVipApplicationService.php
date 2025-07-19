<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommand;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommandHandler;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQuery;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQueryHandle;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Services\UserVipDomainService;

/**
 * @see UserVipOpenCommandHandler::handle()
 * @method bool open(UserVipOpenCommand $command)
 * @method UserVip|null findUserVip(FindUserVipQuery $command)
 */
class UserVipApplicationService extends ApplicationService
{

    public function __construct(
        public UserVipRepositoryInterface $repository,
        public UserVipReadRepositoryInterface $readRepository,
        public UserVipDomainService $domainService,
        public UserVipOrderRepositoryInterface $userVipOrderRepository,
    ) {
    }

    protected static string $modelClass = UserVip::class;

    public static string $hookNamePrefix = 'vip.application.user-vip';

    // 置空


    protected static $macros = [
        'create'      => null,
        'update'      => null,
        'delete'      => null,
        'open'        => UserVipOpenCommandHandler::class, // 开通
        'findUserVip' => FindUserVipQueryHandle::class
    ];


}