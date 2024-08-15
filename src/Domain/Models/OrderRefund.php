<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Order\Domain\Events\RefundAgreedEvent;
use RedJasmine\Order\Domain\Events\RefundAgreedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundCanceledEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReshippedGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReturnedGoodsEvent;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShippableTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderProductTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\OrderFactory;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;


class OrderRefund extends Model
{


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
        'expands'                => 'array',
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

    public function product() : BelongsTo
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
     * 拒绝
     *
     * @param string $reason
     *
     * @return void
     * @throws RefundException
     */
    public function reject(string $reason) : void
    {

        if (!in_array($this->refund_status, [
            RefundStatusEnum::WAIT_SELLER_AGREE,
            RefundStatusEnum::WAIT_SELLER_AGREE_RETURN,
            RefundStatusEnum::WAIT_SELLER_CONFIRM,
        ],            true)) {
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

        if (!in_array($this->refund_status,
            [
                RefundStatusEnum::SELLER_REJECT_BUYER,
                RefundStatusEnum::WAIT_SELLER_AGREE,
                RefundStatusEnum::WAIT_SELLER_AGREE_RETURN
            ], true)) {

            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::REFUND_CANCEL;
        $this->end_time      = now();

        $this->fireModelEvent('canceled');
    }

    /**
     * 同意退款
     *
     * @param Amount|null $amount
     *
     * @return void
     * @throws RefundException
     */
    public function agreeRefund(?Amount $amount = null) : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND
        ],            true)) {
            throw new RefundException();
        }
        // 验证状态
        if (!in_array($this->refund_status, [ RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_CONFIRM, ], true)) {
            throw new RefundException();
        }

        $amount = $amount ?: $this->refund_amount;

        if (bccomp($amount, $this->refund_amount, 2) > 0) {
            throw RefundException::newFromCodes(RefundException::REFUND_AMOUNT_OVERFLOW, '退款金额超出');
        }
        // TODO 什么情况下需要加上邮费
        $this->end_time      = now();
        $this->refund_amount = $amount;
        $this->refund_status = RefundStatusEnum::REFUND_SUCCESS;


        // 设置订单信息
        $this->product->refund_amount = bcadd($this->product->refund_amount, $amount, 2);
        $this->product->refund_status = OrderRefundStatusEnum::PART_REFUND;
        $this->product->refund_time   = $this->product->refund_time ?? now();
        if (bccomp($this->product->refund_amount, $this->product->divided_payment_amount, 2) >= 0) {
            $this->product->refund_status = OrderRefundStatusEnum::ALL_REFUND;
        }
        $this->order->refund_amount = bcadd($this->order->refund_amount, $amount, 2);
        $this->order->refund_status = OrderRefundStatusEnum::PART_REFUND;
        if (bccomp($this->order->refund_amount, $this->order->payment_amount, 2) >= 0) {
            $this->order->refund_status = OrderRefundStatusEnum::ALL_REFUND;
        }
        $this->order->refund_time = $this->order->refund_time ?? now();


        $payment                 = app(OrderFactory::class)->createOrderPayment();
        $payment->order_id       = $this->order_id;
        $payment->refund_id      = $this->id;
        $payment->seller         = $this->seller;
        $payment->buyer          = $this->buyer;
        $payment->amount_type    = AmountTypeEnum::REFUND;
        $payment->payment_amount = bcadd($this->refund_amount, $this->freight_amount, 2);
        $payment->status         = PaymentStatusEnum::WAIT_PAY;
        $this->payments->add($payment);
        $this->fireModelEvent('agreed');
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
     * 同意补发
     * @return void
     * @throws RefundException
     */
    public function agreeReshipment()
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RESHIPMENT,
        ],            true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }

        if ($this->refund_type === RefundTypeEnum::RESHIPMENT) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }
        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_AGREE) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::WAIT_SELLER_RESHIPMENT;

        $this->fireModelEvent('agreedReshipment');
    }

    /**
     * 退货货物时需要确认
     *
     * @return void
     * @throws RefundException
     */
    public function confirm() : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::SERVICE,
            RefundTypeEnum::EXCHANGE,
        ],            true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }
        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_CONFIRM) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::WAIT_SELLER_RESHIPMENT;

        $this->fireModelEvent('confirmed');

    }

    /**
     * 回退货物
     * @throws RefundException
     */
    public function returnGoods(OrderLogistics $orderLogistics) : void
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RETURN_GOODS_REFUND,
            RefundTypeEnum::EXCHANGE,
            RefundTypeEnum::SERVICE,
        ],            true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }

        if ($this->refund_status !== RefundStatusEnum::WAIT_BUYER_RETURN_GOODS) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::WAIT_SELLER_CONFIRM;


        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('returnedGoods');

    }


    /**
     * 再次发货
     * // 换货、维修、补发
     *
     * @param OrderLogistics $orderLogistics
     *
     * @return void
     * @throws RefundException
     */
    public function reshipment(OrderLogistics $orderLogistics) : void
    {

        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_RESHIPMENT) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::REFUND_SUCCESS;
        $this->end_time      = now();

        $orderLogistics->shippable_type = LogisticsShippableTypeEnum::REFUND;

        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('reshipment');


    }
}
