<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelTransferResult extends AbstractChannelResult
{

    public TransferStatusEnum $status;


    public ?string $channelTransferNo;


    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $transferTime;


}
