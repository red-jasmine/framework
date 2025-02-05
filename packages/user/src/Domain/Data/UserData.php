<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserData extends Data
{
    #[WithCast(EnumCast::class, UserTypeEnum::class)]
    public UserTypeEnum $type = UserTypeEnum::PERSONAL;


    public ?string $email;
    public ?string $phoneNumber;
    public ?string $username;
    public ?string $password;

    // 个人资料
    public ?string $nickname;
    public ?string $gender;
    public ?string $birthday;
    public ?string $avatar;
    #[Max(50)]
    public ?string $biography;


}
