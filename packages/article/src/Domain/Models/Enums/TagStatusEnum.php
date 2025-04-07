<?php

namespace RedJasmine\Article\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TagStatusEnum: string
{

    use EnumsHelper;

    case DISABLE = 'disable';


    case ENABLE = 'enable';

    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::ENABLE->value  => __('red-jasmine-article::article-tag.enums.status.disable'),
            self::DISABLE->value => __('red-jasmine-article::article-tag.enums.status.enable'),
        ];

    }


    public static function colors() : array
    {
        return [
            self::ENABLE->value  => 'success',
            self::DISABLE->value => 'danger',
        ];

    }

    public static function icons() : array
    {
        return [
            self::ENABLE->value  => 'heroicon-o-check-circle',
            self::DISABLE->value => 'heroicon-o-no-symbol',
        ];
    }
}
