<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Data\UserSetAccountData;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserSetAccountCommand extends UserSetAccountData
{


}