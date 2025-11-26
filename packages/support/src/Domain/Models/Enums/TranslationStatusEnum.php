<?php

namespace RedJasmine\Support\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TranslationStatusEnum: string
{
    use EnumsHelper;

    case PENDING = 'pending';      // 待翻译
    case TRANSLATED = 'translated'; // 已翻译
    case REVIEWED = 'reviewed';     // 已审核

    public static function labels(): array
    {
        return [
            self::PENDING->value => '待翻译',
            self::TRANSLATED->value => '已翻译',
            self::REVIEWED->value => '已审核',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->value => 'gray',
            self::TRANSLATED->value => 'blue',
            self::REVIEWED->value => 'green',
        ];
    }
}

