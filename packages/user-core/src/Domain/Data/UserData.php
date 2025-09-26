<?php

namespace RedJasmine\UserCore\Domain\Data;

use RedJasmine\UserCore\Domain\Enums\AccountTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserData extends UserBaseInfoData
{
    #[WithCast(EnumCast::class, AccountTypeEnum::class)]
    public ?AccountTypeEnum $accountType = AccountTypeEnum::PERSONAL;

    // 账号信息
    public ?string $email;
    public ?string $phone;
    public ?string $name;
    public ?string $password;


}
