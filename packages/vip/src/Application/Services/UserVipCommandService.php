<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommandHandler;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Services\UserVipDomainService;

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