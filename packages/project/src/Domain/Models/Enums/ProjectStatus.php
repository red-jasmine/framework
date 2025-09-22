<?php

namespace RedJasmine\Project\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProjectStatus: string
{
    use EnumsHelper;

    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case ARCHIVED = 'archived';

    public static function labels(): array
    {
        return [
            self::DRAFT->value => '草稿',
            self::ACTIVE->value => '激活',
            self::PAUSED->value => '暂停',
            self::ARCHIVED->value => '归档',
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRAFT->value => 'gray',
            self::ACTIVE->value => 'green',
            self::PAUSED->value => 'yellow',
            self::ARCHIVED->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::DRAFT->value => 'heroicon-o-document-text',
            self::ACTIVE->value => 'heroicon-o-play',
            self::PAUSED->value => 'heroicon-o-pause',
            self::ARCHIVED->value => 'heroicon-o-archive-box',
        ];
    }
}
