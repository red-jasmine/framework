<?php

namespace RedJasmine\Distribution\Domain\Services;

use Illuminate\Support\Facades\Config;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterCompeteUserModeEnum;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use RedJasmine\Support\Foundation\Service\Service;

class DistributionConfigService extends Service
{
    /**
     * 保护期时间
     *
     * @return TimeConfigData
     */
    public function getProtectionTimeConfig() : TimeConfigData
    {
        return TimeConfigData::from(
            Config::get('red-jasmine-distribution.bind_user.protection_time', [
                'value' => 30,
                'unit'  => 'day'
            ]));
    }

    /**
     * 绑定过期时间
     * @return TimeConfigData
     */
    public function getExpirationTimeConfig() : TimeConfigData
    {
        return TimeConfigData::from(
            Config::get('red-jasmine-distribution.bind_user.protection_time', [
                'value' => 1,
                'unit'  => 'year'
            ]));
    }

    /**
     * 抢客户 为 下单限时
     * @return TimeConfigData
     */
    public function getCompeteUserOrderLimitTimeConfig() : TimeConfigData
    {

        return TimeConfigData::from(
            Config::get('red-jasmine-distribution.compete_user.order_limit_time ', [
                'value' => 1,
                'unit'  => 'hour'
            ]));
    }


    /**
     * 获取抢客模式
     * @return PromoterCompeteUserModeEnum
     */
    public function getCompeteUserMode() : PromoterCompeteUserModeEnum
    {
        return PromoterCompeteUserModeEnum::from(
            Config::get('red-jasmine-distribution.compete_user.mode')
        );
    }

}