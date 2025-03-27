<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipApplicationService extends ApplicationService
{

    public function __construct(
        public VipRepositoryInterface $repository,
        public VipReadRepositoryInterface $readRepository,
    ) {
    }

    protected static string $modelClass = Vip::class;

    public static string $hookNamePrefix = 'vip.application.vip';

    public function findVipType(string $appId, string $type) : ?Vip
    {
        return $this->readRepository->findVipType($appId, $type);
    }
}