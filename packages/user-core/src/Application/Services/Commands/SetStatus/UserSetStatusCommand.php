<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetPassword;

use RedJasmine\Support\Foundation\Data\Data;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserSetStatusCommand extends Data
{

    #[WithCast(EnumCast::class, UserStatusEnum::class)]
    public UserStatusEnum $status;


}