<?php

namespace RedJasmine\Announcement\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AnnouncementStatus: string
{
    use EnumsHelper;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case REVOKED = 'revoked';

    public static function labels(): array
    {
        return [
            self::DRAFT->value => '草稿',
            self::PUBLISHED->value => '已发布',
            self::REVOKED->value => '已撤销',
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRAFT->value => 'gray',
            self::PUBLISHED->value => 'green',
            self::REVOKED->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::DRAFT->value => 'heroicon-o-document-text',
            self::PUBLISHED->value => 'heroicon-o-check-circle',
            self::REVOKED->value => 'heroicon-o-x-circle',
        ];
    }
}
