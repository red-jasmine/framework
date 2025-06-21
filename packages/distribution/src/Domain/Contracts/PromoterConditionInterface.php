<?php

namespace RedJasmine\Distribution\Domain\Contracts;

use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;

interface PromoterConditionInterface
{


    /**
     * 允许申请类型
     * @return array|PromoterApplyTypeEnum[]
     */
    public static function allowApplyType() : array;

    /**
     * 名称
     * @return string
     */
    public static function name() : string;


    /**
     * 名称
     * @return string
     */
    public static function label() : string;

    /**
     * 提示
     * @return string|null
     */
    public static function tips() : ?string;


    /**
     *  单位
     * @return string|null
     */
    public static function unit() : ?string;


    /**
     * 是否满足条件
     *
     * @param  Promoter  $promoter
     * @param  ConditionData  $config
     *
     * @return bool
     */
    public static function isMeet(Promoter $promoter, ConditionData $config) : bool;
}