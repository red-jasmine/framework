<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Enums\OrderProductTypeEnum;
use RedJasmine\Order\Domain\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Enums\RefundTypeEnum;
use RedJasmine\Order\Domain\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Events\RefundAgreedEvent;
use RedJasmine\Order\Domain\Events\RefundAgreedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundCanceledEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReturnedGoodsEvent;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;


class OrderRefund extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    public $incrementing = false;

    protected $casts = [
        'order_product_type' => OrderProductTypeEnum::class,
        'shipping_type'      => ShippingTypeEnum::class,
        'refund_type'        => RefundTypeEnum::class,
        'refund_status'      => RefundStatusEnum::class,
        'good_status'        => RefundGoodsStatusEnum::class,
        'phase'              => RefundPhaseEnum::class,
        'has_good_return'    => 'boolean',
        'end_time'           => 'datetime',
        'images'             => 'array',
        'extends'            => 'array',
    ];

    protected $dispatchesEvents = [
        'agreed'              => RefundAgreedEvent::class,
        'rejected'            => RefundRejectedEvent::class,
        'canceled'            => RefundCanceledEvent::class,
        'agreedReturnGoods'   => RefundAgreedReturnGoodsEvent::class,
        'rejectedReturnGoods' => RefundRejectedReturnGoodsEvent::class,
        'returnedGoods'       => RefundReturnedGoodsEvent::class
    ];


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function orderProduct() : BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'id');
    }


    public function logistics() : MorphMany
    {
        return $this->morphMany(OrderLogistics::class, 'shippable');
    }


    /**
     * 同意退款
     *
     * @param string|null $amount
     *
     * @return void
     * @throws RefundException
     */
    public function agree(?string $amount = null) : void
    {
        // 验证状态
        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_CONFIRM_GOODS, ], true)) {
            throw new RefundException();
        }
        if (!in_array($this->refund_type, [ RefundTypeEnum::REFUND_ONLY, RefundTypeEnum::RETURN_GOODS_REFUND ], true)) {
            throw new RefundException();
        }
        $amount = $amount ?: $this->refund_amount;
        // TODO 验证金额
        $this->end_time                    = now();
        $this->refund_amount               = $amount;
        $this->refund_status               = RefundStatusEnum::REFUND_SUCCESS;
        $this->orderProduct->refund_amount = bcadd($this->orderProduct->refund_amount, $amount, 2);


        $this->fireModelEvent('agreed');
    }


    public function reject(string $reason) : void
    {

        // TODO 状态
        $this->reject_reason = $reason;
        $this->refund_status = RefundStatusEnum::SELLER_REJECT_BUYER;

        $this->fireModelEvent('rejected');

    }

    /**
     * @return void
     */
    public function cancel() : void
    {
        $this->refund_status = RefundStatusEnum::REFUND_CLOSED;
        $this->end_time      = now();

        $this->fireModelEvent('canceled');
    }


    // 同意退货

    /**
     * 同意退货
     * @return void
     * @throws RefundException
     */
    public function agreeReturnGoods() : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RETURN_GOODS_REFUND,
            RefundTypeEnum::EXCHANGE_GOODS,
            RefundTypeEnum::SERVICE,
        ],            true)) {
            throw new RefundException('refund type not allowed');
        }
        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE_RETURN ], true)) {
            throw new RefundException('refund status not allowed');
        }

        $this->refund_status = RefundStatusEnum::WAIT_BUYER_RETURN_GOODS;


        $this->fireModelEvent('agreedReturnGoods');

    }


    public function rejectReturnGoods(string $reason) : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RETURN_GOODS_REFUND,
            RefundTypeEnum::EXCHANGE_GOODS,
            RefundTypeEnum::SERVICE,
        ],            true)) {
            throw new RefundException('refund type not allowed');
        }
        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE_RETURN ], true)) {
            throw new RefundException('refund status not allowed');
        }

        $this->refund_status = RefundStatusEnum::SELLER_REJECT_BUYER;
        $this->reject_reason = $reason;


        $this->fireModelEvent('rejectedReturnGoods');

    }

    public function returnGoods(OrderLogistics $orderLogistics) : void
    {

        $this->refund_status = RefundStatusEnum::WAIT_SELLER_CONFIRM_GOODS;

        $orderLogistics->shippable_type = 'refund';

        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('returnedGoods');

    }


}
