<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommand;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommandHandler;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Services\UserVipDomainService;

/**
 * @see UserVipOpenCommandHandler::handle()
 * @method bool open(UserVipOpenCommand $command)
 */
class UserVipCommandService extends ApplicationCommandService
{

    public function __construct(
        public UserVipRepositoryInterface $repository,
        public UserVipReadRepositoryInterface $readRepository,
        public UserVipDomainService $domainService,
        public UserVipOrderRepositoryInterface $userVipOrderRepository,
    ) {
    }

    protected static string $modelClass = UserVip::class;

    public static string $hookNamePrefix = 'vip.application.command.user-vip';
    protected static     $macros         = [
        'open' => UserVipOpenCommandHandler::class,
    ];


}