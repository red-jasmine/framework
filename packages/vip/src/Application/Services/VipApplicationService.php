<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipApplicationService extends ApplicationService
{

    public function __construct(
        public VipRepositoryInterface $repository,
    ) {
    }

    protected static string $modelClass = Vip::class;

    public static string $hookNamePrefix = 'vip.application.vip';

    public function findVipType(string $biz, string $type) : ?Vip
    {
        return $this->repository->findVipType($biz, $type);
    }
}