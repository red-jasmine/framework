<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipCommandService extends ApplicationCommandService
{

    public function __construct(
        public VipRepositoryInterface $repository,
        public VipReadRepositoryInterface $readRepository,
    ) {
    }

    protected static string $modelClass = Vip::class;

    public static string $hookNamePrefix = 'vip.application.command.vip';


}