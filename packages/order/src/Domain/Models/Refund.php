<?php

namespace RedJasmine\Order\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Events\RefundAgreedEvent;
use RedJasmine\Order\Domain\Events\RefundAgreedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundCanceledEvent;
use RedJasmine\Order\Domain\Events\RefundCreatedEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedEvent;
use RedJasmine\Order\Domain\Events\RefundRejectedReturnGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReshippedGoodsEvent;
use RedJasmine\Order\Domain\Events\RefundReturnedGoodsEvent;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Generator\RefundNoGeneratorInterface;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Order\Domain\Models\Extensions\RefundExtension;
use RedJasmine\Order\Domain\Models\Features\HasStar;
use RedJasmine\Order\Domain\Models\Features\HasUrge;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


/**
 * @property string $refund_no
 * @property RefundStatusEnum $refund_status
 * @property RefundTypeEnum $refund_type
 * @property Money $refund_freight_amount
 * @property Money $refund_product_amount
 */
class Refund extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    use HasUrge;

    use HasStar;

    use HasCommonAttributes;


    protected   $table                    = 'order_refunds';
    public bool $withTradePartiesNickname = true;

    public $incrementing = false;

    public function newInstance($attributes = [], $exists = false) : Refund
    {

        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('extension', $instance->extension()->newModelInstance(['id' => $instance->getKey()]));
        }
        if (!$instance->exists && !empty($attributes)) {
            $instance->generateNo();
        }

        return $instance;
    }

    protected function generateNo() : void
    {
        if (!$this->refund_no) {

            $this->refund_no = app(RefundNoGeneratorInterface::class)->generator($this);
        }

    }

    public function extension() : HasOne
    {
        return $this->hasOne(RefundExtension::class, 'id', 'id');
    }


    public function casts() : array
    {
        return array_merge([
            'order_product_type'    => ProductTypeEnum::class,
            'shipping_type'         => ShippingTypeEnum::class,
            'refund_type'           => RefundTypeEnum::class,
            'refund_status'         => RefundStatusEnum::class,
            'good_status'           => RefundGoodsStatusEnum::class,
            'phase'                 => RefundPhaseEnum::class,
            'has_good_return'       => 'boolean',
            'end_time'              => 'datetime',
            'refund_product_amount' => MoneyCast::class,
            'refund_freight_amount' => MoneyCast::class,
            'total_refund_amount'   => MoneyCast::class,
        ], $this->getCommonAttributesCast());
    }


    protected $dispatchesEvents = [
        'created'             => RefundCreatedEvent::class,
        'agreed'              => RefundAgreedEvent::class,
        'rejected'            => RefundRejectedEvent::class,
        'canceled'            => RefundCanceledEvent::class,
        'agreedReturnGoods'   => RefundAgreedReturnGoodsEvent::class,
        'rejectedReturnGoods' => RefundRejectedReturnGoodsEvent::class,
        'returnedGoods'       => RefundReturnedGoodsEvent::class,
        'reshippedGoods'      => RefundReshippedGoodsEvent::class,
        'urge'                => RefundAgreedEvent::class,
    ];
    protected $observables      = [
        'agreed',
        'rejected',
        'canceled',
        'agreedReturnGoods',
        'rejectedReturnGoods',
        'returnedGoods',
        'reshippedGoods',
        'urge',
    ];

    protected $fillable = [
        'app_id',
        'seller_id',
        'seller',
        'buyer_id',
        'buyer',
        'currency',
    ];


    public function setOrder(Order $order) : void
    {
        $this->setRelation('order', $order);
        $this->order_no   = $order->order_no;
        $this->app_id     = $order->app_id;
        $this->seller     = $order->seller;
        $this->buyer      = $order->buyer;
        $this->source     = $order->source;
        $this->store      = $order->store;
        $this->channel    = $order->channel;
        $this->guide      = $order->guide;
        $this->order_type = $order->order_type;
        //$this->shipping_type = $order->shipping_type;

    }


    public function setOrderProduct(OrderProduct $orderProduct) : void
    {
        $this->setRelation('product', $orderProduct);

        $this->order_product_no        = $orderProduct->order_product_no;
        $this->order_product_type      = $orderProduct->order_product_type;
        $this->shipping_type           = $orderProduct->shipping_type;
        $this->product_type            = $orderProduct->product_type;
        $this->product_id              = $orderProduct->product_id;
        $this->sku_id                  = $orderProduct->sku_id;
        $this->title                   = $orderProduct->title;
        $this->sku_name                = $orderProduct->sku_name;
        $this->image                   = $orderProduct->image;
        $this->outer_product_id        = $orderProduct->outer_product_id;
        $this->outer_sku_id            = $orderProduct->outer_sku_id;
        $this->barcode                 = $orderProduct->barcode;
        $this->unit_quantity           = $orderProduct->unit_quantity;
        $this->unit                    = $orderProduct->unit;
        $this->category_id             = $orderProduct->category_id;
        $this->brand_id                = $orderProduct->brand_id;
        $this->product_group_id        = $orderProduct->product_group_id;
        $this->tax_rate                = $orderProduct->tax_rate;
        $this->quantity                = $orderProduct->quantity;
        $this->price                   = $orderProduct->price;
        $this->total_price             = $orderProduct->total_price;
        $this->discount_amount         = $orderProduct->discount_amount;
        $this->product_amount          = $orderProduct->product_amount;
        $this->divided_discount_amount = $orderProduct->divided_discount_amount;
        $this->freight_amount          = $orderProduct->freight_amount;
        $this->tax_amount              = $orderProduct->tax_amount;
        $this->payable_amount          = $orderProduct->payable_amount;
        $this->payment_amount          = $orderProduct->payment_amount;

    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_no', 'order_no');
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_no', 'order_product_no');
    }


    public function logistics() : HasMany
    {
        return $this->hasMany(OrderLogistics::class, 'entity_id', 'refund_no')->where('entity_type', EntityTypeEnum::REFUND->value);
    }

    public function cardKeys() : HasMany
    {
        return $this->hasMany(OrderCardKey::class, 'entity_id', 'refund_no')->where('entity_type', EntityTypeEnum::REFUND->value);
    }


    public function payments() : HasMany
    {
        return $this->hasMany(OrderPayment::class, 'entity_id', 'refund_no')->where('entity_type', EntityTypeEnum::REFUND->value);
    }

    /**
     * 检查当前是否是销售阶段
     *
     * 此方法用于判断当前退款阶段是否为销售阶段，即是否可以进行销售操作
     * 它通过比较当前阶段与预定义的销售阶段枚举值来确定
     *
     * @return bool 如果当前阶段是销售阶段，则返回true；否则返回false
     */
    public function isSalePhase() : bool
    {
        // 检查当前阶段是否为销售阶段
        if ($this->phase === RefundPhaseEnum::ON_SALE) {
            return true;
        }
        return false;
    }


    /**
     * 拒绝
     *
     * @param  string  $reason
     *
     * @return void
     * @throws RefundException
     */
    public function reject(string $reason) : void
    {

        if (!$this->isAllowReject()) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->extension->reject_reason = $reason;
        $this->refund_status            = RefundStatusEnum::SELLER_REJECT_BUYER;
        $this->fireModelEvent('rejected', false);
    }

    public function isAllowReject() : bool
    {
        if (!in_array($this->refund_status, [
            RefundStatusEnum::WAIT_SELLER_AGREE,
            RefundStatusEnum::WAIT_SELLER_AGREE_RETURN,
            RefundStatusEnum::WAIT_SELLER_CONFIRM,
        ], true)) {
            return false;
        }

        return true;
    }

    /**
     * 取消
     * @return void
     * @throws RefundException
     */
    public function cancel() : void
    {

        if (!$this->isAllowCancel()) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }


        $this->refund_status = RefundStatusEnum::CANCEL;
        $this->end_time      = now();


        $this->fireModelEvent('canceled', false);
    }

    public function isAllowCancel() : bool
    {
        if (!in_array($this->refund_status,
            [
                RefundStatusEnum::SELLER_REJECT_BUYER,
                RefundStatusEnum::WAIT_SELLER_AGREE,
                RefundStatusEnum::WAIT_SELLER_AGREE_RETURN
            ], true)) {
            return false;

        }
        return true;
    }

    /**
     * 同意退款
     *
     * @param  Money|null  $amount
     *
     * @return void
     * @throws RefundException
     */
    public function agreeRefund(?Money $amount = null) : void
    {
        if (!$this->isAllowAgreeRefund()) {
            throw new RefundException();
        }

        $amount = $amount ?: $this->total_refund_amount;

        if ($amount->compare($this->total_refund_amount) > 0) {
            throw RefundException::newFromCodes(RefundException::REFUND_AMOUNT_OVERFLOW, '退款金额超出');

        }
        $this->end_time            = now();
        $this->total_refund_amount = $amount;
        $this->refund_status       = RefundStatusEnum::FINISHED;
        // 如果是售中阶段同步状态
        if ($this->phase === RefundPhaseEnum::ON_SALE) {
            // 设置订单商品项信息

            // 同步 订单商品信息和状态
            $this->product->refund_amount = $this->product->refund_amount->add($this->total_refund_amount);
            //$this->product->refund_status = $this->refund_status;
            $this->product->refund_time = $this->product->refund_time ?? now();
            // 如果全款退，那么久是无效订单
            if ($this->product->refund_amount->compare($this->product->payable_amount) >= 0) {
                // TODO 退款流程指正 提款状态进行处理
                $this->product->order_status = OrderStatusEnum::CLOSED;
                // TODO 设置退款状态
            }


            // 设置订单项
            $this->order->refund_amount = $this->order->refund_amount->add($this->total_refund_amount);
            $this->order->refund_time   = $this->order->refund_time ?? now();


            // 如果是全款退 ，那么就关闭订单
            if ($this->order->isEffective() === false) {
                $this->order->close();
            }


        }


        // 只有在已经支付了情况下 才去创建支付单 TODO

        // 创建支付单
        $payment                 = OrderPayment::make();
        $payment->order_no       = $this->order_no;
        $payment->app_id         = $this->app_id;
        $payment->seller         = $this->seller;
        $payment->buyer          = $this->buyer;
        $payment->entity_type    = EntityTypeEnum::REFUND;
        $payment->entity_id      = $this->refund_no;
        $payment->amount_type    = AmountTypeEnum::REFUND;
        $payment->payment_amount = $this->total_refund_amount;
        $payment->status         = PaymentStatusEnum::WAIT_PAY;
        $this->payments->add($payment);


        $this->fireModelEvent('agreed', false);
    }

    /**
     * 是否允许同意退款
     * @return bool
     */
    public function isAllowAgreeRefund() : bool
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND
        ], true)) {
            return false;
        }
        if (!in_array($this->refund_status,
            [RefundStatusEnum::WAIT_SELLER_AGREE, RefundStatusEnum::WAIT_SELLER_CONFIRM,], true)) {
            return false;
        }
        return true;
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
            RefundTypeEnum::WARRANTY,
        ], true)) {
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
    public function agreeReshipment() : void
    {

        if (!$this->isAllowAgreeReshipment()) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::WAIT_SELLER_RESHIPMENT;

        $this->fireModelEvent('agreedReshipment', false);
    }


    public function isAllowAgreeReshipment() : bool
    {
        if (!in_array($this->refund_type, [
            RefundTypeEnum::RESHIPMENT,
        ], true)) {
            return false;
        }


        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_AGREE) {
            return false;
        }
        return true;
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
            RefundTypeEnum::WARRANTY,
            RefundTypeEnum::EXCHANGE,
        ], true)) {
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
            RefundTypeEnum::WARRANTY,
        ], true)) {
            throw  RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }

        if ($this->refund_status !== RefundStatusEnum::WAIT_BUYER_RETURN_GOODS) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }

        $this->refund_status = RefundStatusEnum::WAIT_SELLER_CONFIRM;


        $orderLogistics->entity_type = EntityTypeEnum::REFUND;
        $orderLogistics->entity_id   = $this->refund_no;
        $orderLogistics->seller_type = $this->seller_type;
        $orderLogistics->seller_id   = $this->seller_id;
        $orderLogistics->buyer_type  = $this->buyer_type;
        $orderLogistics->buyer_id    = $this->buyer_id;
        $orderLogistics->order_no    = $this->order_no;
        $orderLogistics->app_id      = $this->app_id;


        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('returnedGoods');

    }


    /**
     * 再次发货
     * // 换货、维修、补发
     *
     * @param  OrderLogistics  $orderLogistics
     *
     * @return void
     * @throws RefundException
     */
    public function logisticsReshipment(OrderLogistics $orderLogistics) : void
    {

        if (!$this->isAllowReshipment()) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::FINISHED;
        $this->end_time      = now();

        $orderLogistics->entity_type = EntityTypeEnum::REFUND;
        $orderLogistics->entity_id   = $this->refund_no;
        $orderLogistics->order_no    = $this->order_no;
        $orderLogistics->seller_type = $this->seller_type;
        $orderLogistics->seller_id   = $this->seller_id;
        $orderLogistics->buyer_type  = $this->buyer_type;
        $orderLogistics->buyer_id    = $this->buyer_id;

        $this->logistics->add($orderLogistics);

        $this->fireModelEvent('reshipment', false);

    }

    /**
     * @param  OrderCardKey  $cardKey
     *
     * @return void
     * @throws RefundException
     */
    public function cardKeyReshipment(OrderCardKey $cardKey) : void
    {
        if (!$this->isAllowReshipment()) {
            throw  RefundException::newFromCodes(RefundException::REFUND_STATUS_NOT_ALLOW);
        }
        $this->refund_status = RefundStatusEnum::FINISHED;

        $this->end_time = now();

        $this->addCardKey($cardKey);

        $this->fireModelEvent('reshipment', false);
    }

    public function addCardKey(OrderCardKey $cardKey) : void
    {
        $cardKey->seller           = $this->seller;
        $cardKey->buyer            = $this->buyer;
        $cardKey->order_no         = $this->order_no;
        $cardKey->app_id           = $this->app_id;
        $cardKey->entity_id        = $this->refund_no;
        $cardKey->order_product_no = $this->order_product_no;
        $cardKey->entity_type      = EntityTypeEnum::REFUND;

        $this->cardKeys->add($cardKey);
    }


    public function isAllowReshipment() : bool
    {
        if ($this->refund_status !== RefundStatusEnum::WAIT_SELLER_RESHIPMENT) {
            return false;
        }
        return true;
    }

    public function remarks(TradePartyEnums $tradeParty, string $remarks = null, bool $isAppend = false) : void
    {
        // 根据交易双方类型动态确定备注信息字段名
        $field = $tradeParty->value.'_remarks';

        $model = $this;
        // 在确定的对象上添加或更新备注信息
        if ($isAppend && blank($model->extension->{$field})) {
            $isAppend = false;
        }
        if ($isAppend) {
            $model->extension->{$field} .= "\n\r".$remarks;
        } else {
            $model->extension->{$field} = $remarks;
        }

    }


    // |---------------scopes----------------------------

    public function scopeWaitSellerHandle(Builder $builder) : Builder
    {
        $builder->whereIn('refund_status', [
            RefundStatusEnum::WAIT_SELLER_AGREE,
            RefundStatusEnum::WAIT_SELLER_AGREE_RETURN,
            RefundStatusEnum::WAIT_SELLER_RESHIPMENT,
        ]);
        return $builder;
    }

    public function scopeWaitSellerConfirm(Builder $builder) : Builder
    {
        $builder->where('refund_status', RefundStatusEnum::WAIT_SELLER_CONFIRM);
        return $builder;
    }

    public function scopeWaitBuyerHandle(Builder $builder) : Builder
    {
        $builder->whereIn('refund_status', [
            RefundStatusEnum::WAIT_BUYER_RETURN_GOODS,
            RefundStatusEnum::SELLER_REJECT_BUYER,
        ]);
        return $builder;
    }

    public function scopeRefundSuccess(Builder $builder) : Builder
    {
        $builder->where('refund_status', RefundStatusEnum::FINISHED);
        return $builder;
    }

    public function scopeRefundCancel(Builder $builder) : Builder
    {
        $builder->where('refund_status', RefundStatusEnum::CANCEL);
        return $builder;
    }

    public function scopeOnCancelClosed(Builder $builder) : Builder
    {
        return $builder->whereIn('order_status', [RefundStatusEnum::CLOSED, RefundStatusEnum::CANCEL]);
    }

}
