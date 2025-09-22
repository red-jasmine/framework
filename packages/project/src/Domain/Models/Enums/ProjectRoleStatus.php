<?php

namespace RedJasmine\Project\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProjectRoleStatus: string
{
    use EnumsHelper;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function labels(): array
    {
        return [
            self::ACTIVE->value => '激活',
            self::INACTIVE->value => '禁用',
        ];
    }

    public static function colors(): array
    {
        return [
            self::ACTIVE->value => 'green',
            self::INACTIVE->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ACTIVE->value => 'heroicon-o-check-circle',
            self::INACTIVE->value => 'heroicon-o-x-circle',
        ];
    }
}
