<?php

namespace RedJasmine\Article\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ArticleContentTypeEnum: string
{
    use EnumsHelper;

    case  RICH = 'rich';
    case  MARKDOWN = 'markdown';
    case  TEXT = 'text';


    public static function labels() : array
    {

        return [
            self::RICH->value     => __('red-jasmine-article::article.enums.content_type.rich'),
            self::MARKDOWN->value => __('red-jasmine-article::article.enums.content_type.markdown'),
            self::TEXT->value     => __('red-jasmine-article::article.enums.content_type.text'),
        ];
    }


}
