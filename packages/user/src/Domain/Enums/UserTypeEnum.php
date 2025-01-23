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
            self::COMPANY->value      => '公司',
            self::ORGANIZATION->value => '行政机构',
            self::PERSONAL->value     => '个人',
        ];

    }


}
