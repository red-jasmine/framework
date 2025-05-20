<?php

namespace RedJasmine\Admin\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户类型
 */
enum AdminTypeEnum: string
{

    use EnumsHelper;

    case ROOT = 'root';

    case ADMIN = 'admin';


    public static function labels() : array
    {
        return [
            self::ROOT->value  => __('red-jasmine-admin::admin.enums.type.root'),
            self::ADMIN->value => __('red-jasmine-admin::admin.enums.type.admin'),

        ];

    }


}
