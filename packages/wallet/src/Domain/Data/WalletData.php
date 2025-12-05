<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class WalletData extends Data
{

    public UserInterface $owner;


    #[Max(32)]
    public string $type;


    #[Max(3)]
    public string $currency = 'CNY';

    /**
     * @var WalletStatusEnum
     */
    #[WithCast(EnumCast::class, WalletStatusEnum::class)]
    public WalletStatusEnum $status = WalletStatusEnum::ENABLE;


}