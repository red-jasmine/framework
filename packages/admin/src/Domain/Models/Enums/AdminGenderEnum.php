<?php

namespace RedJasmine\Admin\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AdminGenderEnum: string
{

    use EnumsHelper;

    case  SECRECY = 'secrecy';

    case MALE = 'male';

    case FEMALE = 'female';


    public static function labels() : array
    {
        return [
            self::SECRECY->value => __('red-jasmine-admin::admin.enums.gender.secrecy'),
            self::MALE->value    => __('red-jasmine-admin::admin.enums.gender.male'),
            self::FEMALE->value  => __('red-jasmine-admin::admin.enums.gender.female'),

        ];
    }
}
