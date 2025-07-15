<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class RuleValue extends ValueObject
{
    /**
     * 规则对象类型
     * product 商品
     * category 分类
     * brand 品牌
     * user_group 用户组
     * user_tags 用户标签
     */
    public RuleObjectTypeEnum $objectType;


    /**
     * 对象值
     */
    public string $objectValue;

}