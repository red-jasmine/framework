<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RuleObjectTypeEnum: string
{
    use EnumsHelper;

    case SELLER = 'seller'; // 卖家
    case PRODUCT = 'product'; // 商品
    case CATEGORY = 'category'; // 分类
    case BRAND = 'brand'; // 品牌
    case USER_GROUP = 'user_group'; // 用户组
    case CUSTOMER_GROUP = 'customer_group'; // 客户分组
    case USER_RECEIVE_LIMIT = 'user_receive_limit'; // 用户领取限制


    public function allowCheckTypes() : array
    {
        return [
            self::PRODUCT->value            => RuleCheckTypeEnum::USAGE,
            self::SELLER->value             => RuleCheckTypeEnum::USAGE,
            self::CATEGORY->value           => RuleCheckTypeEnum::USAGE,
            self::BRAND->value              => RuleCheckTypeEnum::USAGE,
            self::USER_GROUP->value         => RuleCheckTypeEnum::RECEIVE,
            self::CUSTOMER_GROUP->value     => RuleCheckTypeEnum::RECEIVE,
            self::USER_RECEIVE_LIMIT->value => RuleCheckTypeEnum::RECEIVE,
        ];
    }


    public function isAllowCheckType(RuleCheckTypeEnum $checkType) : bool
    {
        return $this->getAllowCheckType() === $checkType;
    }

    /**
     * 允许的配置类型
     * @return RuleCheckTypeEnum
     */
    public function getAllowCheckType() : RuleCheckTypeEnum
    {
        return $this->allowCheckTypes()[$this->value];
    }

    public static function labels() : array
    {
        return [
            self::PRODUCT->value    => __('red-jasmine-coupon::coupon.enums.rule_object_type.product'),
            self::CATEGORY->value   => __('red-jasmine-coupon::coupon.enums.rule_object_type.category'),
            self::BRAND->value      => __('red-jasmine-coupon::coupon.enums.rule_object_type.brand'),
            self::USER_GROUP->value => __('red-jasmine-coupon::coupon.enums.rule_object_type.user_group'),
        ];
    }

    public static function colors() : array
    {
        return [
            self::PRODUCT->value    => 'primary',
            self::CATEGORY->value   => 'secondary',
            self::BRAND->value      => 'info',
            self::USER_GROUP->value => 'warning',
        ];
    }

    public static function icons() : array
    {
        return [
            self::PRODUCT->value    => 'heroicon-o-cube',
            self::CATEGORY->value   => 'heroicon-o-folder',
            self::BRAND->value      => 'heroicon-o-tag',
            self::USER_GROUP->value => 'heroicon-o-user-group',
        ];
    }
} 