<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ActivityTypeEnum: string
{
    use EnumsHelper;
    
    case FLASH_SALE = 'flash_sale';
    case GROUP_BUYING = 'group_buying';
    case BARGAIN = 'bargain';
    case DISCOUNT = 'discount';
    case FULL_REDUCTION = 'full_reduction';
    case BUNDLE = 'bundle';
    
    public static function labels(): array
    {
        return [
            self::FLASH_SALE->value => '秒杀活动',
            self::GROUP_BUYING->value => '拼团活动',
            self::BARGAIN->value => '砍价活动',
            self::DISCOUNT->value => '折扣活动',
            self::FULL_REDUCTION->value => '满减活动',
            self::BUNDLE->value => '凑单活动',
        ];
    }
    
    public static function colors(): array
    {
        return [
            self::FLASH_SALE->value => 'red',
            self::GROUP_BUYING->value => 'blue',
            self::BARGAIN->value => 'green',
            self::DISCOUNT->value => 'orange',
            self::FULL_REDUCTION->value => 'purple',
            self::BUNDLE->value => 'pink',
        ];
    }
    
    public static function icons(): array
    {
        return [
            self::FLASH_SALE->value => 'heroicon-o-bolt',
            self::GROUP_BUYING->value => 'heroicon-o-users',
            self::BARGAIN->value => 'heroicon-o-currency-dollar',
            self::DISCOUNT->value => 'heroicon-o-tag',
            self::FULL_REDUCTION->value => 'heroicon-o-shopping-cart',
            self::BUNDLE->value => 'heroicon-o-gift',
        ];
    }
}
