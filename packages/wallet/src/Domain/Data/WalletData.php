<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class WalletData extends Data
{

    public UserInterface $owner;


    #[Max(32)]
    public string $type;

    /**
     * @var WalletStatusEnum
     */
    #[WithCast(EnumCast::class, WalletStatusEnum::class)]
    public WalletStatusEnum $status = WalletStatusEnum::ENABLE;


}