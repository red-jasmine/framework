<?php

namespace RedJasmine\Distribution\Domain\Services\Conditions;

use RedJasmine\Distribution\Domain\Contracts\PromoterConditionInterface;
use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;

class BuyProductCondition implements PromoterConditionInterface
{
    /**
     * 允许申请类型
     * @return array|PromoterApplyTypeEnum[]
     */
    public static function allowApplyType() : array
    {
        return [
            PromoterApplyTypeEnum::REGISTER,
            PromoterApplyTypeEnum::UPGRADE
        ];
    }

    public const string NAME = 'buy-product';

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
        return '购买指定商品';
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
        return null;
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