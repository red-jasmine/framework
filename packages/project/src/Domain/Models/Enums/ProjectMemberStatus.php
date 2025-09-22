<?php

namespace RedJasmine\Project\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProjectMemberStatus: string
{
    use EnumsHelper;

    case PENDING = 'pending';
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case LEFT = 'left';

    public static function labels(): array
    {
        return [
            self::PENDING->value => '待确认',
            self::ACTIVE->value => '正常',
            self::PAUSED->value => '暂停',
            self::LEFT->value => '已退出',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->value => 'yellow',
            self::ACTIVE->value => 'green',
            self::PAUSED->value => 'gray',
            self::LEFT->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::PENDING->value => 'heroicon-o-clock',
            self::ACTIVE->value => 'heroicon-o-check-circle',
            self::PAUSED->value => 'heroicon-o-pause-circle',
            self::LEFT->value => 'heroicon-o-x-circle',
        ];
    }
}
