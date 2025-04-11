<?php

namespace RedJasmine\User\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户类型
 */
enum UserTypeEnum: string
{

    use EnumsHelper;

    case PERSONAL = 'personal';

    case COMPANY = 'company';

    case ORGANIZATION = 'organization';

    public static function labels() : array
    {
        return [
            self::COMPANY->value      => __('red-jasmine-user::user.enums.type.company'),
            self::ORGANIZATION->value => __('red-jasmine-user::user.enums.type.organization'),
            self::PERSONAL->value     => __('red-jasmine-user::user.enums.type.personal'),
        ];

    }


}
