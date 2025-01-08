<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class TransferCreateData extends Data
{

    public int $merchantAppId;

    /**
     * 内部渠道应用ID
     * @var int
     */
    public int $channelAppId;

    public ?string $merchantTransferNo;

    public string $subject;

    public ?string $description;

    #[WithCast(EnumCast::class, TransferSceneEnum::class)]
    public TransferSceneEnum $sceneCode = TransferSceneEnum::TRANSFER;

    public Money $amount;

    public TransferPayee $payee;

    public string $payer;
}
