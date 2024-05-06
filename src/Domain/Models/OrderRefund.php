<?php

namespace RedJasmine\Order\Domain\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Enums\OrderProductTypeEnum;
use RedJasmine\Order\Domain\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Enums\PaymentStatusEnum;
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
use RedJasmine\Order\Domain\Events\RefundReshippedGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReturnedGoodsEvent;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\OrderFactory;
use RedJasmine\Support\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Support\Foundation\HasServiceContext;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;


class OrderRefund extends Model
{
    use HasServiceContext;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    public $incrementing = false;

    protected $casts = [
        'order_product_type'     => OrderProductTypeEnum::class,
        'shipping_type'          => ShippingTypeEnum::class,
        'refund_type'            => RefundTypeEnum::class,
        'refund_status'          => RefundStatusEnum::class,
        'good_status'            => RefundGoodsStatusEnum::class,
        'phase'                  => RefundPhaseEnum::class,
        'has_good_return'        => 'boolean',
        'end_time'               => 'datetime',
        'images'                 => 'array',
        'extends'                => 'array',
        'price'                  => AmountCastTransformer::class,
        'cost_price'             => AmountCastTransformer::class,
        'product_amount'         => AmountCastTransformer::class,
        'payable_amount'         => AmountCastTransformer::class,
        'payment_amount'         => AmountCastTransformer::class,
        'divided_payment_amount' => AmountCastTransformer::class,
        'refund_amount'          => AmountCastTransformer::class,
    ];

    protected $dispatchesEvents = [
        'agreed'              => RefundAgreedEvent::class,
        'rejected'            => RefundRejectedEvent::class,
        'canceled'            => RefundCanceledEvent::class,
        'agreedReturnGoods'   => RefundAgreedReturnGoodsEvent::class,
        'rejectedReturnGoods' => RefundRejectedReturnGoodsEvent::class,
        'returnedGoods'       => RefundReturnedGoodsEvent::class,
        'reshippedGoods'      => RefundReshippedGoodsEvent::class,
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

    public function payments() : HasMany
    {
        return $this->hasMany(OrderPayment::class, 'refund_id', 'id');
    }


    /**
     * 同意退款
     *
     * @param string|null $amount
     *
     * @return void
     * @throws RefundException
     * @throws Exception
     */
    public function agree(?string $amount = null) : void
    {

        if (!in_array($this->refund_type, [ RefundTypeEnum::REFUND_ONLY, RefundTypeEnum::RETURN_GOODS_REFUND ], true)) {
            throw new RefundException();
        }
        // 验证状态
        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_CONFIRM_GOODS, ], true)) {
            throw new RefundException();
        }

        $amount = $amount ?: $this->refund_amount;


        // TODO 验证金额
        $this->end_time      = now();
        $this->refund_amount = $amount;
        $this->refund_status = RefundStatusEnum::REFUND_SUCCESS;

        // TODO 退邮费
        // 设置退款单状态
        $this->orderProduct->refund_amount = bcadd($this->orderProduct->refund_amount, $amount, 2);
        $this->orderProduct->refund_status = OrderRefundStatusEnum::PART_REFUND;
        $this->orderProduct->refund_time   = $this->orderProduct->refund_time ?? now();
        if (bccomp($this->orderProduct->refund_amount, $this->orderProduct->divided_payment_amount, 2) >= 0) {
            $this->orderProduct->refund_status = OrderRefundStatusEnum::ALL_REFUND;
        }
        $this->orderProduct->order->refund_amount = bcadd($this->orderProduct->order->refund_amount, $amount, 2);
        $this->orderProduct->order->refund_status = OrderRefundStatusEnum::PART_REFUND;
        if (bccomp($this->orderProduct->order->refund_amount, $this->orderProduct->order->payment_amount, 2) >= 0) {
            $this->orderProduct->order->refund_status = OrderRefundStatusEnum::ALL_REFUND;
        }
        $this->orderProduct->order->refund_time = $this->orderProduct->order->refund_time ?? now();


        $payment                 = app(OrderFactory::class)->createOrderPayment();
        $payment->order_id       = $this->order_id;
        $payment->refund_id      = $this->id;
        $payment->seller         = $this->seller;
        $payment->buyer          = $this->buyer;
        $payment->amount_type    = AmountTypeEnum::REFUND;
        $payment->payment_amount = $this->refund_amount;
        $payment->status         = PaymentStatusEnum::WAIT_PAY;
        $this->payments->add($payment);
        $this->fireModelEvent('agreed');
    }


    /**
     * 拒绝退款
     *
     * @param string $reason
     *
     * @return void
     * @throws RefundException
     */
    public function reject(string $reason) : void
    {

        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_AGREE_RETURN ], true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->reject_reason = $reason;
        $this->refund_status = RefundStatusEnum::SELLER_REJECT_BUYER;
        $this->fireModelEvent('rejected');
    }


    /**
     * 取消
     * @return void
     * @throws RefundException
     */
    public function cancel() : void
    {

        if (!in_array($this->refund_status, [ RefundStatusEnum::SELLER_REJECT_BUYER, RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_AGREE_RETURN ], true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::REFUND_CANCEL;
        $this->end_time      = now();

        $this->fireModelEvent('canceled');
    }


    /**
     * 同意退货
     * @return void
     * @throws RefundException
     */
    public function agreeReturnGoods() : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RETURN_GOODS_REFUND,
            RefundTypeEnum::EXCHANGE,
            RefundTypeEnum::SERVICE,
        ],            true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }
        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_AGREE_RETURN) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::WAIT_BUYER_RETURN_GOODS;
        $this->fireModelEvent('agreedReturnGoods');

    }


    /**
     * 拒绝退货
     * @throws RefundException
     */
    public function rejectReturnGoods(string $reason) : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RETURN_GOODS_REFUND,
            RefundTypeEnum::EXCHANGE,
            RefundTypeEnum::SERVICE,
        ],            true)) {

            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }
        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_AGREE_RETURN) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }


        $this->refund_status = RefundStatusEnum::SELLER_REJECT_BUYER;

        $this->reject_reason = $reason;

        $this->fireModelEvent('rejectedReturnGoods');
    }

    /**
     * @throws RefundException
     */
    public function returnGoods(OrderLogistics $orderLogistics) : void
    {

        if ($this->refund_status !== RefundStatusEnum::WAIT_BUYER_RETURN_GOODS) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::WAIT_SELLER_CONFIRM_GOODS;

        $orderLogistics->shippable_type = 'refund';

        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('returnedGoods');

    }


    /**
     * 再次发货
     *
     * @param OrderLogistics $orderLogistics
     *
     * @return void
     * @throws RefundException
     */
    public function reshipGoods(OrderLogistics $orderLogistics) : void
    {

        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_CONFIRM_GOODS) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::REFUND_SUCCESS;
        $this->end_time      = now();

        $orderLogistics->shippable_type = 'refund';

        $this->logistics->add($orderLogistics);

    }
}
