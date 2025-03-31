<?php

namespace RedJasmine\Interaction\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CommentStatusEnum: string
{

    use EnumsHelper;

    case DISABLED = 'disabled';
    case ENABLED = 'enabled';

    public static function labels() : array
    {
        return [
            self::DISABLED->value => '删除',
            self::ENABLED->value  => '正常',
        ];
    }
}
