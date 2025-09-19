<?php

namespace RedJasmine\Organization\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum MemberStatusEnum: string
{
    use EnumsHelper;

    case ACTIVE    = 'active';
    case PROBATION = 'probation';
    case RESIGNED  = 'resigned';

    public static function labels(): array
    {
        return [
            self::ACTIVE->value    => '在职',
            self::PROBATION->value => '试用',
            self::RESIGNED->value  => '离职',
        ];
    }
}


