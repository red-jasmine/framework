<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserData extends UserBaseInfoData
{
    #[WithCast(EnumCast::class, UserTypeEnum::class)]
    public ?UserTypeEnum $type = UserTypeEnum::PERSONAL;

    // 账号信息
    public ?string $email;
    public ?string $phone;
    public ?string $name;


    public ?string $password;


}
