<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ActivityOrderStatusEnum: string
{
    use EnumsHelper;
    
    case PARTICIPATED = 'participated';
    case ORDERED = 'ordered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    
    public static function labels(): array
    {
        return [
            self::PARTICIPATED->value => '已参与',
            self::ORDERED->value => '已下单',
            self::COMPLETED->value => '已完成',
            self::CANCELLED->value => '已取消',
        ];
    }
    
    public static function colors(): array
    {
        return [
            self::PARTICIPATED->value => 'blue',
            self::ORDERED->value => 'yellow',
            self::COMPLETED->value => 'green',
            self::CANCELLED->value => 'red',
        ];
    }
    
    public static function icons(): array
    {
        return [
            self::PARTICIPATED->value => 'heroicon-o-hand-raised',
            self::ORDERED->value => 'heroicon-o-shopping-cart',
            self::COMPLETED->value => 'heroicon-o-check-circle',
            self::CANCELLED->value => 'heroicon-o-x-circle',
        ];
    }
}