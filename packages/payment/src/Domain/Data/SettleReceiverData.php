<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class SettleReceiverData extends Data
{
    public int    $systemMerchantAppId;
    #[Max(32)]
    public string $receiverType;
    #[Max(64)]
    public string $receiverId;
    #[Max(64)]
    public string $channelCode;
    #[Max(64)]
    public string $channelMerchantId = SettleReceiver::ALL_CHANNEL_MERCHANT; // 通用的

    #[WithCast(EnumCast::class, AccountTypeEnum::class)]
    public AccountTypeEnum $accountType;
    #[Max(64)]
    public string          $account;
    #[Max(100)]
    public string          $name;

    #[WithCast(EnumCast::class, SettleRelationTypeEnum::class)]
    public SettleRelationTypeEnum $relationType = SettleRelationTypeEnum::USER;


    #[WithCast(EnumCast::class, CertTypeEnum::class)]
    public ?CertTypeEnum $certType;

    public ?string $certNo;


}
