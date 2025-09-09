<?php

namespace RedJasmine\Vip\Domain\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use RedJasmine\Vip\Domain\Data\OpenUserVipData;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Models\UserVipOrder;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;

class UserVipDomainService
{

    public function __construct(
        protected UserVipRepositoryInterface $repository,
        protected VipDomainService $vipDomainService,
    ) {

        $this->orders = Collection::make([]);
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
        $vip = $this->vipDomainService->validate($data->biz, $data->type);

        $userVip         = $this->findUserVip($data);
        $userVip->vip_id = $vip->id;
        $currentEndTime  = $userVip->getCurrentEndTime();
        // 设置结束时间
        $userVip->addEndTime($data->timeValue, $data->timeUnit);

        // 添加开通记录
        $this->addOrder($userVip, $data, $currentEndTime);

        return $userVip;
    }

    protected function findUserVip(OpenUserVipData $data) : UserVip
    {
        // 查询用户VIP
        $userVip             = $this->repository->findVipByOwner($data->owner, $data->biz, $data->type);
        $userVip             = $userVip ?? UserVip::make();
        $userVip->owner      = $data->owner;
        $userVip->biz     = $data->biz;
        $userVip->type       = $data->type;
        $userVip->level      = $userVip->level ?? $userVip->defaultLevel();
        $userVip->start_time = $userVip->start_time ?? now();
        return $userVip;
    }


    protected Collection $orders;

    public function getOrders() : Collection
    {
        return $this->orders;
    }

    public function flushOrders() : void
    {
        $this->orders = Collection::make([]);
    }


    protected function addOrder(UserVip $userVip, OpenUserVipData $data, Carbon $endTime) : void
    {
        $userVipOrder = UserVipOrder::make();

        $userVipOrder->start_time   = $endTime;
        $userVipOrder->end_time     = $userVip->getCurrentEndTime();
        $userVipOrder->order_time   = Carbon::now();
        $userVipOrder->owner        = $data->owner;
        $userVipOrder->biz       = $data->biz;
        $userVipOrder->payment_type = $data->paymentType;
        $userVipOrder->payment_id   = $data->paymentId;
        $userVipOrder->type         = $data->type;
        $userVipOrder->time_unit    = $data->timeUnit;
        $userVipOrder->time_value   = $data->timeValue;


        $this->orders->push($userVipOrder);

    }

}