<?php

namespace RedJasmine\Payment\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelTransferData extends Data
{

    public ?string $message = null;

    public TransferStatusEnum $status;

    public ?string $channelTransferNo;

    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $transferTime;


}
