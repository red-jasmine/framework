<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;
    
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ACTIVE = 'active';
    case ENDED = 'ended';
    
    public static function labels(): array
    {
        return [
            self::PENDING->value => '待审核',
            self::APPROVED->value => '已审核',
            self::REJECTED->value => '已拒绝',
            self::ACTIVE->value => '进行中',
            self::ENDED->value => '已结束',
        ];
    }
    
    public static function colors(): array
    {
        return [
            self::PENDING->value => 'yellow',
            self::APPROVED->value => 'blue',
            self::REJECTED->value => 'red',
            self::ACTIVE->value => 'green',
            self::ENDED->value => 'gray',
        ];
    }
    
    public static function icons(): array
    {
        return [
            self::PENDING->value => 'heroicon-o-clock',
            self::APPROVED->value => 'heroicon-o-check-circle',
            self::REJECTED->value => 'heroicon-o-x-circle',
            self::ACTIVE->value => 'heroicon-o-play-circle',
            self::ENDED->value => 'heroicon-o-stop-circle',
        ];
    }
}