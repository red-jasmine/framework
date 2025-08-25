<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SkuParticipationModeEnum: string
{
    use EnumsHelper;
    
    case ALL_SKUS = 'all_skus';
    case SPECIFIC_SKUS = 'specific_skus';
    
    public static function labels(): array
    {
        return [
            self::ALL_SKUS->value => '所有SKU参与',
            self::SPECIFIC_SKUS->value => '指定SKU参与',
        ];
    }
    
    public static function colors(): array
    {
        return [
            self::ALL_SKUS->value => 'blue',
            self::SPECIFIC_SKUS->value => 'green',
        ];
    }
    
    public static function icons(): array
    {
        return [
            self::ALL_SKUS->value => 'heroicon-o-squares-2x2',
            self::SPECIFIC_SKUS->value => 'heroicon-o-square-3-stack-3d',
        ];
    }
}