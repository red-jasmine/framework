<?php

namespace RedJasmine\Vip\Domain;

use Exception;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;

class VipDomainService
{
    public function __construct(
        protected VipReadRepositoryInterface $vipReadRepository,
    ) {
    }


    /**
     * @param  string  $appID
     * @param  string  $type
     *
     * @return bool
     * @throws VipException
     */
    public function validate(string $appID, string $type) : bool
    {

        $vip = $this->vipReadRepository->findVipType($appID, $type);
        if (!$vip) {
            throw new VipException('vip not found');
        }

        return true;
    }
}