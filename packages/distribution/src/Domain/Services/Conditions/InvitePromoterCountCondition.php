<?php

namespace RedJasmine\Distribution\Domain\Services\Conditions;

use RedJasmine\Distribution\Domain\Contracts\PromoterConditionInterface;
use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;

/**
 *
 */
class InvitePromoterCountCondition implements PromoterConditionInterface
{
    /**
     * 允许申请类型
     * @return array|PromoterApplyTypeEnum[]
     */
    public static function allowApplyType() : array
    {
        return [
            PromoterApplyTypeEnum::UPGRADE,
        ];
    }

    public const string NAME = 'invite-promoter-count';

    /**
     * 名称
     * @return string
     */
    public static function name() : string
    {
        return static::NAME;
    }

    /**
     * 名称
     * @return string
     */
    public static function label() : string
    {
        return '邀请分销员';
    }

    /**
     * 提示
     * @return string|null
     */
    public static function tips() : ?string
    {
        return '';
    }

    /**
     *  单位
     * @return string|null
     */
    public static function unit() : ?string
    {
        return '人';
    }

    /**
     * 获取值
     *
     * @param  Promoter  $promoter
     *
     * @return true
     */
    public static function getPromoterValue(Promoter $promoter) : true
    {

        // TODO 查询订单
        return true;
    }


    /**
     * 是否满足条件
     *
     * @param  Promoter  $promoter
     * @param  ConditionData  $config
     *
     * @return bool
     */
    public static function isMeet(Promoter $promoter, ConditionData $config) : bool
    {
        // TODO 判断条件是否满足
        return true;
    }
}