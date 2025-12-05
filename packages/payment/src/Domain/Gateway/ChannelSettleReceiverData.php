<?php

namespace RedJasmine\Payment\Domain\Gateway;


use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ChannelSettleReceiverData extends Data
{

    #[WithCast(EnumCast::class, AccountTypeEnum::class)]
    public ?AccountTypeEnum $accountType;
    #[Max(64)]
    public ?string          $account;
    #[Max(100)]
    public ?string          $name;

}
