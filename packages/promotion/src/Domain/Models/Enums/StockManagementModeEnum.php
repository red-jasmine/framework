<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum StockManagementModeEnum: string
{
    use EnumsHelper;
    
    case UNIFIED = 'unified';
    case INDIVIDUAL = 'individual';
    
    public static function labels(): array
    {
        return [
            self::UNIFIED->value => '统一管理',
            self::INDIVIDUAL->value => '独立管理',
        ];
    }
    
    public static function colors(): array
    {
        return [
            self::UNIFIED->value => 'blue',
            self::INDIVIDUAL->value => 'green',
        ];
    }
    
    public static function icons(): array
    {
        return [
            self::UNIFIED->value => 'heroicon-o-equals',
            self::INDIVIDUAL->value => 'heroicon-o-adjustments-horizontal',
        ];
    }
}