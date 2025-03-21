<?php

namespace RedJasmine\Article\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ArticleStatusEnum: string
{
    use EnumsHelper;

    case  DRAFT = 'draft';
    case  PUBLISHED = 'published';
    case  DELETED = 'deleted';

    public static function labels() : array
    {
        return [
            self::DRAFT->value     => '草稿',
            self::PUBLISHED->value => '发布',
            self::DELETED->value   => '删除',
        ];
    }
}
