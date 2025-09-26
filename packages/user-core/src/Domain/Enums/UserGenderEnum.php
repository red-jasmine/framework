<?php

namespace RedJasmine\UserCore\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum UserGenderEnum: string
{

    use EnumsHelper;

    case  SECRECY = 'secrecy';

    case MALE = 'male';

    case FEMALE = 'female';


    public static function labels() : array
    {
        return [
            self::SECRECY->value => __('red-jasmine-user::user.enums.gender.secrecy'),
            self::MALE->value    => __('red-jasmine-user::user.enums.gender.male'),
            self::FEMALE->value  => __('red-jasmine-user::user.enums.gender.female'),

        ];
    }
}
