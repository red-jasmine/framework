<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserSetStatusCommand extends Data
{

    #[WithCast(EnumCast::class, UserStatusEnum::class)]
    public UserStatusEnum $status;


}