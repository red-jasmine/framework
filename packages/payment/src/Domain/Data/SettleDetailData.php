<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;

class SettleDetailData extends Data
{
    // 根据用户 查询 分账接收方
    #[Max(32)]
    public string $receiverType;
    #[Max(64)]
    public string $receiverId;

    public string $subject;

    public ?string $description = null;

    public Money $amount;

}
