<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class RefundCreateCommand extends Data
{


    public int $id;

    public int $orderProductId;

    /**
     * 申请类型
     * @var RefundTypeEnum
     */
    public RefundTypeEnum $refundType;

    /**
     * 退款金额
     * @var ?Money
     */
    public ?Money $refundAmount = null;

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


    /**
     * 包含邮费
     * @var string|int|float|null
     */
    protected string|int|float|null $freightAmount = 0;

}
