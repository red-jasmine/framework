<?php

namespace RedJasmine\Vip\Domain\Services;

use RedJasmine\Vip\Domain\Data\OpenUserVipData;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\VipDomainService;

class UserVipDomainService
{

    public function __construct(
        protected UserVipReadRepositoryInterface $readRepository,
        protected VipDomainService $vipDomainService,
    ) {
    }


    /**
     * 开通VIP
     *
     * @param  OpenUserVipData  $data
     *
     * @return UserVip
     * @throws VipException
     */
    public function open(OpenUserVipData $data) : UserVip
    {
        // 验证VIP 是否有效
        $this->vipDomainService->validate($data->appID, $data->type);

        $userVip = $this->readRepository->findVipByOwner($data->owner, $data->appID, $data->type);

        $userVip         = $userVip ?? UserVip::make();
        $userVip->owner  = $data->owner;
        $userVip->app_id = $data->appID;
        $userVip->type   = $data->type;
        $userVip->level  = $userVip->level ?? $userVip->defaultLevel();

        $userVip->start_time = $userVip->start_time ?? now();

        $data->isForever ? $userVip->setForever() : $userVip->addEndTime($data->timeUnit->value, $data->timeValue);


        // 添加开通记录

        return $userVip;
    }


}