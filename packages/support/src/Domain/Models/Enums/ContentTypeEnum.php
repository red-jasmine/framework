<?php

namespace RedJasmine\Support\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ContentTypeEnum: string
{
    use EnumsHelper;

    case  RICH = 'rich';
    case  MARKDOWN = 'markdown';
    case  TEXT = 'text';


    public static function labels() : array
    {

        return [
            self::RICH->value     => __('red-jasmine-support::support.enums.content_type.rich'),
            self::MARKDOWN->value => __('red-jasmine-support::support.enums.content_type.markdown'),
            self::TEXT->value     => __('red-jasmine-support::support.enums.content_type.text'),
        ];
    }


}
