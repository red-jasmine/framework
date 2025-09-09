<?php

namespace RedJasmine\Vip\Domain\Services;

use Exception;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipDomainService
{
    public function __construct(
        protected VipRepositoryInterface $vipRepository,
    ) {
    }


    /**
     * @param  string  $biz
     * @param  string  $type
     *
     * @return Vip
     * @throws VipException
     */
    public function validate(string $biz, string $type) : Vip
    {

        $vip = $this->vipRepository->findVipType($biz, $type);

        if (!$vip) {
            throw new VipException('vip not found');
        }

        return $vip;
    }
}