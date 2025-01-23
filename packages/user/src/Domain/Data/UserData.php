<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserData extends Data
{

    public ?string $email;
    public ?string $phoneNumber;
    public ?string $username;

    public ?string $password;

    public ?string $nickname;
    public ?string $gender;

    #[WithCast(EnumCast::class, UserTypeEnum::class)]
    public UserTypeEnum $type = UserTypeEnum::PERSONAL;


    public ?string $birthday;


    public ?string $avatar;

}
