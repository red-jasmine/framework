<?php

namespace RedJasmine\Article\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ArticleContentTypeEnum: string
{
    use EnumsHelper;

    case  MARKDOWN = 'markdown';
    case  RICH = 'rich';
    case  TEXT = 'text';


    public static function labels() : array
    {

        return [
            self::MARKDOWN->value => __('red-jasmine-article::article.enums.content_type.markdown'),
            self::RICH->value     => __('red-jasmine-article::article.enums.content_type.rich'),
            self::TEXT->value     => __('red-jasmine-article::article.enums.content_type.text'),
        ];
    }


}
