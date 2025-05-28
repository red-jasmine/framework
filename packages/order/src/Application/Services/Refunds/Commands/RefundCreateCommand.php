<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class RefundCreateCommand extends Data
{
    public string $orderNo;

    public string $orderProductNo;

    /**
     * 申请类型
     * @var RefundTypeEnum
     */
    #[WithCast(EnumCast::class,RefundTypeEnum::class)]
    public RefundTypeEnum $refundType;


    public ?Money $refundProductAmount = null;


    public ?Money $refundFreightAmount = null;

    /**
     * 原因
     * @var string|null
     */
    public ?string $reason;

    /**
     * 描述
     * @var string|null
     */
    public ?string $description;
    /**
     * 图片
     * @var array|null
     */
    public ?array $images;

    /**
     * 外部退款单ID
     * @var string|null
     */
    public ?string $outerRefundId = null;


}
