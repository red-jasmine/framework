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
            self::DRAFT->value     => __('red-jasmine-article::article.enums.status.draft'),
            self::PUBLISHED->value => __('red-jasmine-article::article.enums.status.published'),
            self::DELETED->value   => __('red-jasmine-article::article.enums.status.deleted'),
        ];
    }
}
