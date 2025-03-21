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
            self::MARKDOWN->value => 'Markdown',
            self::RICH->value     => '富文本',

            self::TEXT->value => '文本',
        ];
    }


}
