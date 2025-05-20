<?php

namespace RedJasmine\Admin\Domain\Data;

use RedJasmine\Admin\Domain\Models\Enums\AdminTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class AdminData extends AdminBaseInfoData
{
    #[WithCast(EnumCast::class, AdminTypeEnum::class)]
    public ?AdminTypeEnum $type = AdminTypeEnum::ADMIN;

    // 账号信息
    public ?string $email;
    public ?string $phone;
    public ?string $name;

    public ?string $password;
}