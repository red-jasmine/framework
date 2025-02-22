<?php

namespace RedJasmine\Vip\Domain\Services;

use Exception;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;

class VipDomainService
{
    public function __construct(
        protected VipReadRepositoryInterface $vipReadRepository,
    ) {
    }


    /**
     * @param  string  $appId
     * @param  string  $type
     *
     * @return Vip
     * @throws VipException
     */
    public function validate(string $appId, string $type) : Vip
    {

        $vip = $this->vipReadRepository->findVipType($appId, $type);

        if (!$vip) {
            throw new VipException('vip not found');
        }

        return $vip;
    }
}