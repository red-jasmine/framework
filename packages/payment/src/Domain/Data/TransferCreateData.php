<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Support\Data\Data;
use Cknow\Money\Money;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class TransferCreateData extends Data
{

    public int $merchantAppId;

    public string $subject;

    public Money $amount;

    public TransferPayee $payee;


    #[WithCast(EnumCast::class, TransferSceneEnum::class)]
    public TransferSceneEnum $sceneCode = TransferSceneEnum::TRANSFER;

    /**
     * @var string
     */
    public string $methodCode;
    /**
     * 渠道应用ID
     * @var ?string
     */
    public ?string $channelAppId;





    public ?string $merchantTransferNo;


    public ?string $description;


    public string $payer;
}
