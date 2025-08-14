<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 消息优先级枚举
 */
enum MessagePriorityEnum: string
{
    use EnumsHelper;

    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public static function labels(): array
    {
        return [
            self::LOW->value => '低',
            self::NORMAL->value => '普通',
            self::HIGH->value => '高',
            self::URGENT->value => '紧急',
        ];
    }

    public static function colors(): array
    {
        return [
            self::LOW->value => 'gray',
            self::NORMAL->value => 'primary',
            self::HIGH->value => 'warning',
            self::URGENT->value => 'danger',
        ];
    }

    public static function icons(): array
    {
        return [
            self::LOW->value => 'heroicon-o-minus',
            self::NORMAL->value => 'heroicon-o-equals',
            self::HIGH->value => 'heroicon-o-plus',
            self::URGENT->value => 'heroicon-o-fire',
        ];
    }

    /**
     * 获取优先级数值
     */
    public function getPriorityValue(): int
    {
        return match ($this) {
            self::LOW => 1,
            self::NORMAL => 2,
            self::HIGH => 3,
            self::URGENT => 4,
        };
    }
}
