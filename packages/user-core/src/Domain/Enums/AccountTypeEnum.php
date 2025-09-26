<?php

namespace RedJasmine\UserCore\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户类型
 */
enum AccountTypeEnum: string
{

    use EnumsHelper;

    case PERSONAL = 'personal';

    case COMPANY = 'company';

    case ORGANIZATION = 'organization';

    public static function labels() : array
    {
        return [
            self::PERSONAL->value     => __('red-jasmine-user-core::user.enums.account_type.personal'),
            self::COMPANY->value      => __('red-jasmine-user-core::user.enums.account_type.company'),
            self::ORGANIZATION->value => __('red-jasmine-user-core::user.enums.account_type.organization'),

        ];

    }


}
