<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;

class UserVipCommandService extends ApplicationCommandService
{

    public function __construct(
        public UserVipRepositoryInterface $repository,
        public UserVipReadRepositoryInterface $readRepository,
    ) {
    }

    protected static string $modelClass = UserVip::class;

    public static string $hookNamePrefix = 'vip.application.command.user-vip';


}