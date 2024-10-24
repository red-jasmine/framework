<?php


namespace RedJasmine\Order\Domain\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Order\Domain\Events\OrderCanceledEvent;
use RedJasmine\Order\Domain\Events\OrderConfirmedEvent;
use RedJasmine\Order\Domain\Events\OrderCreatedEvent;
use RedJasmine\Order\Domain\Events\OrderFinishedEvent;
use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use RedJasmine\Order\Domain\Events\OrderPayingEvent;
use RedJasmine\Order\Domain\Events\OrderProgressEvent;
use RedJasmine\Order\Domain\Events\OrderShippedEvent;
use RedJasmine\Order\Domain\Events\OrderShippingEvent;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Models\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Order\Domain\Services\OrderRefundService;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use Spatie\LaravelData\WithData;


class Order extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    use WithData;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    public bool $withTradePartiesNickname = true;

    public    $incrementing     = false;
    protected $dispatchesEvents = [
        'created'   => OrderCreatedEvent::class,
        'canceled'  => OrderCanceledEvent::class,
        'paying'    => OrderPayingEvent::class,
        'paid'      => OrderPaidEvent::class,
        'shipping'  => OrderShippingEvent::class,
        'shipped'   => OrderShippedEvent::class,
        'progress'  => OrderProgressEvent::class,
        'finished'  => OrderFinishedEvent::class,
        'confirmed' => OrderConfirmedEvent::class,
    ];
    protected $observables      = [
        'shipping',
        'shipped',
        'paying',
        'paid',
        'progress',
        'canceled',
        'confirmed',
    ];
    protected $casts            = [
        'order_type'             => OrderTypeEnum::class,
        'pay_type'               => PayTypeEnum::class,
        'order_status'           => OrderStatusEnum::class,
        'payment_status'         => PaymentStatusEnum::class,
        'shipping_status'        => ShippingStatusEnum::class,
        'refund_status'          => OrderRefundStatusEnum::class,
        'created_time'           => 'datetime',
        'payment_time'           => 'datetime',
        'close_time'             => 'datetime',
        'shipping_time'          => 'datetime',
        'collect_time'           => 'datetime',
        'dispatch_time'          => 'datetime',
        'signed_time'            => 'datetime',
        'confirm_time'           => 'datetime',
        'refund_time'            => 'datetime',
        'rate_time'              => 'datetime',
        'contact'                => AesEncrypted::class,
        'is_seller_delete'       => 'boolean',
        'is_buyer_delete'        => 'boolean',
        'freight_amount'         => AmountCastTransformer::class,
        'discount_amount'        => AmountCastTransformer::class,
        'product_payable_amount' => AmountCastTransformer::class,
        'payable_amount'         => AmountCastTransformer::class,
        'payment_amount'         => AmountCastTransformer::class,
        'refund_amount'          => AmountCastTransformer::class,
        'commission_amount'      => AmountCastTransformer::class,
        'cost_amount'            => AmountCastTransformer::class,
        'tax_amount'             => AmountCastTransformer::class,
        'product_amount'         => AmountCastTransformer::class,
        'service_amount'         => AmountCastTransformer::class,
    ];

    /**
     * @return static
     * @throws Exception
     */
    public static function newModel() : static
    {
        $order     = new static();
        $order->id = $order->newUniqueId();

        $orderInfo     = new OrderInfo();
        $orderInfo->id = $order->id;
        $order->setRelation('info', $orderInfo);
        $order->setRelation('products', Collection::make());
        $order->setRelation('payments', Collection::make());
        $order->setRelation('address', null);
        return $order;
    }

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix', 'jasmine_') . 'orders';
    }

    public function info() : HasOne
    {
        return $this->hasOne(OrderInfo::class, 'id', 'id');
    }

    public function products() : HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function address() : HasOne
    {
        return $this->hasOne(OrderAddress::class, 'id', 'id');
    }

    public function logistics() : MorphMany
    {
        return $this->morphMany(OrderLogistics::class, 'shippable');
    }

    public function payments() : HasMany
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }


    public function guide() : MorphTo
    {
        return $this->morphTo('guide', 'guide_type', 'guide_id');
    }


    public function setGuideAttribute(?UserInterface $user) : static
    {
        $this->setAttribute('guide_type', $user?->getType());
        $this->setAttribute('guide_id', $user?->getID());
        $this->setAttribute('guide_name', $user?->getNickname());

        return $this;
    }

    public function store() : MorphTo
    {
        return $this->morphTo('store', 'store_type', 'store_id');
    }

    public function setStoreAttribute(?UserInterface $user) : static
    {
        $this->setAttribute('store_type', $user?->getType());
        $this->setAttribute('store_id', $user?->getID());
        $this->setAttribute('store_name', $user?->getNickname());
        return $this;
    }

    public function channel() : MorphTo
    {
        return $this->morphTo('channel', 'channel_type', 'channel_id');
    }

    public function setChannelAttribute(?UserInterface $user) : static
    {
        $this->setAttribute('channel_type', $user?->getType());
        $this->setAttribute('channel_id', $user?->getID());
        $this->setAttribute('channel_name', $user?->getNickname());
        return $this;
    }

    public function addProduct(OrderProduct $orderProduct) : static
    {
        $orderProduct->order_id       = $this->id;
        $orderProduct->buyer_type     = $this->buyer_type;
        $orderProduct->buyer_id       = $this->buyer_id;
        $orderProduct->seller_type    = $this->seller_type;
        $orderProduct->seller_id      = $this->seller_id;
        $orderProduct->progress_total = (int)bcmul($orderProduct->num, $orderProduct->unit_quantity, 0);
        $orderProduct->created_time   = now();
        $this->products->add($orderProduct);
        return $this;
    }


    public function setAddress(OrderAddress $orderAddress) : static
    {
        $orderAddress->id = $this->id;

        $this->setRelation('address', $orderAddress);
        return $this;
    }

    public function addLogistics(OrderLogistics $logistics) : void
    {
        $this->logistics->add($logistics);
    }

    /**
     * 有效 子单数量
     * @return int
     */
    public function productEffectiveCount() : int
    {
        $count = 0;

        $this->products->each(function (OrderProduct $orderProduct) use (&$count) {
            if ($orderProduct->isEffective()) {
                $count++;
            }
        });

        return $count;
    }

    public function shipping() : void
    {

        $effectiveAndNotShippingCount = 0;
        // 统计有效单 但是还没有完成发货的订单
        $this->products->each(function (OrderProduct $orderProduct) use (&$effectiveAndNotShippingCount) {

            if ($orderProduct->isEffective() &&
                in_array($orderProduct->shipping_status,
                    [ null, ShippingStatusEnum::NIL,
                      ShippingStatusEnum::WAIT_SEND,
                      ShippingStatusEnum::PART_SHIPPED
                    ], true)) {
                $effectiveAndNotShippingCount++;
            }
        });

        // 如果还有未完成发货的订单商品 那么订单只能是部分发货
        $this->shipping_status = $effectiveAndNotShippingCount > 0 ? ShippingStatusEnum::PART_SHIPPED : ShippingStatusEnum::SHIPPED;
        $this->shipping_time   = $this->shipping_time ?? now();

        $event = $this->shipping_status === ShippingStatusEnum::SHIPPED ? 'shipped' : 'shipping';

        $this->fireModelEvent($event, false);

    }

    /**
     * @param string|null $reason
     *
     * @return void
     * @throws OrderException
     */
    public function cancel(?string $reason = null) : void
    {
        // 什么情况下可以取消
        if ($this->order_status === OrderStatusEnum::CANCEL) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        // 未发货、未支付、情况下可以取消
        if (in_array($this->payment_status,
                     [ PaymentStatusEnum::PAID, PaymentStatusEnum::PART_PAY, PaymentStatusEnum::NO_PAYMENT, ], true)) {
            throw OrderException::newFromCodes(OrderException::PAYMENT_STATUS_NOT_ALLOW);
        }
        $this->order_status  = OrderStatusEnum::CANCEL;
        $this->cancel_reason = $reason;
        $this->close_time    = now();
        $this->products->each(function (OrderProduct $orderProduct) {
            $orderProduct->order_status = OrderStatusEnum::CANCEL;
            $orderProduct->close_time   = now();
        });

        $this->fireModelEvent('canceled', false);
    }

    /**
     * 发起支付
     *
     * @param OrderPayment $orderPayment
     *
     * @return void
     * @throws OrderException
     */
    public function paying(OrderPayment $orderPayment) : void
    {
        if (!in_array($this->payment_status, [ PaymentStatusEnum::WAIT_PAY, PaymentStatusEnum::NIL ], true)) {
            throw  OrderException::newFromCodes(OrderException::PAYMENT_STATUS_NOT_ALLOW);
        }
        // 添加支付单
        $orderPayment->order_id    = $this->id;
        $orderPayment->buyer_type  = $this->buyer_type;
        $orderPayment->buyer_id    = $this->buyer_id;
        $orderPayment->seller_type = $this->seller_type;
        $orderPayment->seller_id   = $this->seller_id;
        $orderPayment->status      = PaymentStatusEnum::PAYING;

        $this->addPayment($orderPayment);
        // 设置为支付中
        if (in_array($this->payment_status, [ PaymentStatusEnum::WAIT_PAY, PaymentStatusEnum::NIL ], true)) {
            $this->payment_status = PaymentStatusEnum::PAYING;
        }
        $this->products->each(function (OrderProduct $orderProduct) {
            $orderProduct->payment_status = PaymentStatusEnum::PAYING;
        });

        $this->fireModelEvent('paying', false);
    }

    public function addPayment(OrderPayment $orderPayment) : void
    {

        $this->payments->add($orderPayment);
    }

    /**
     * @param OrderPayment $orderPayment
     *
     * @return void
     * @throws OrderException
     */
    public function paid(OrderPayment $orderPayment) : void
    {
        if (!in_array($this->payment_status, [
            PaymentStatusEnum::NIL, PaymentStatusEnum::WAIT_PAY, PaymentStatusEnum::PAYING, PaymentStatusEnum::PART_PAY
        ],            true)) {
            throw  OrderException::newFromCodes(OrderException::PAYMENT_STATUS_NOT_ALLOW);
        }

        $orderPayment->status = PaymentStatusEnum::PAID;

        $this->payment_amount = bcadd($this->payment_amount, $orderPayment->payment_amount, 2);
        $this->payment_status = PaymentStatusEnum::PART_PAY;
        $this->payment_time   = $this->payment_time ?? now();
        if (bccomp($this->payment_amount, $this->payable_amount, 2) >= 0) {
            $this->payment_status = PaymentStatusEnum::PAID;
        }
        $this->products->each(function (OrderProduct $orderProduct) {
            $orderProduct->payment_status = $this->payment_status;
            $orderProduct->payment_time   = $this->payment_time;
            // 全部支付成功是 才能设置 订单商品的支付金额
            if ($orderProduct->payment_status = PaymentStatusEnum::PAID) {
                $orderProduct->payment_amount = $orderProduct->payable_amount;
            }
        });


        $this->fireModelEvent('paid', false);
    }

    /**
     * 订单确认
     *
     * @param int|null $orderProductId
     *
     * @return void
     * @throws OrderException
     */
    public function confirm(?int $orderProductId = null) : void
    {

        if (in_array($this->order_status,
                     [
                         OrderStatusEnum::CANCEL,
                         OrderStatusEnum::FINISHED,
                         OrderStatusEnum::CLOSED
                     ],
                     true)) {
            throw new OrderException('订单已完成');
        }
        // 只有在 部分发货情况下  才允许 传入子单号 单独确认搜获
        if (filled($orderProductId)) {
            // 子单 分开确认
            // 如果是部分发货  子单号必须填写
            if ($this->shipping_status === ShippingStatusEnum::PART_SHIPPED) {
                throw new OrderException('发货状态不一致');
            }

            $orderProduct = $this->products->where('id', $orderProductId)->firstOrFail();

            if ($orderProduct->shipping_status !== ShippingStatusEnum::SHIPPED) {
                throw new OrderException('子单未发货完成');
            }
            $orderProduct->signed_time  = now();
            $orderProduct->confirm_time = now();

        } else {
            if ($this->shipping_status !== ShippingStatusEnum::SHIPPED) {
                throw new OrderException('发货状态不一致');
            }

            $this->products->each(function (OrderProduct $orderProduct) {
                if ($orderProduct->isEffective()) {
                    // 已经确认了的 无需再次确认
                    $orderProduct->confirm_time = $orderProduct->confirm_time ?? now();
                    $orderProduct->signed_time  = $orderProduct->confirm_time ?? now();
                }
            });

            $this->confirm_time = $this->confirm_time ?? now();
            $this->signed_time  = $this->signed_time ?? now();
        }


        $this->fireModelEvent('confirmed', false);
    }

    /**
     * @param int $orderProductId
     * @param int $progress
     * @param bool $isAppend
     * @param bool $isAllowLess
     *
     * @return int 最新的进度
     * @throws OrderException
     */
    public function setProductProgress(
        int  $orderProductId,
        int  $progress,
        bool $isAppend = false,
        bool $isAllowLess = false
    ) : int
    {
        $orderProduct = $this->products->where('id', $orderProductId)->firstOrFail();
        $oldProgress  = (int)$orderProduct->progress;
        $newProgress  = $isAppend ? ((int)bcadd($oldProgress, $progress, 0)) : $progress;
        if ($oldProgress === $newProgress) {
            return $newProgress;
        }
        //判断是否允许更小
        if ($isAllowLess === false && bccomp($newProgress, $oldProgress, 0) < 0) {
            throw OrderException::newFromCodes(OrderException::PROGRESS_NOT_ALLOW_LESS, '进度不允许小于之前的值');
        }

        $orderProduct->progress = $newProgress;

        $this->fireModelEvent('progress', false);
        return (int)$orderProduct->progress;
    }

    public function refunds() : HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_id', 'id');
    }

    /**
     * @param OrderRefund $orderRefund
     *
     * @return void
     * @throws RefundException
     */
    public function createRefund(OrderRefund $orderRefund) : void
    {
        app(OrderRefundService::class)->create($this, $orderRefund);
    }

    public function create() : static
    {
        // 计算金额
        $this->calculateAmount();
        $this->created_time = now();
        return $this;
    }

    /**
     * 计算金额
     * @return $this
     */
    public function calculateAmount() : static
    {
        // 统计商品金额
        $this->calculateProductsAmount();
        // 汇总订单金额
        $this->calculateOrderAmount();
        // 分摊订单数据
        $this->calculateDivideDiscountAmount();
        return $this;
    }

    /**
     * 计算商品金额
     * @return void
     */
    protected function calculateProductsAmount() : void
    {
        foreach ($this->products as $product) {
            // 成本金额
            $product->cost_amount = bcmul($product->num, $product->cost_price->value(), 2);

            // 商品总金额   < 0 TODO 验证金额
            $product->product_amount = bcmul($product->num, $product->price->value(), 2);
            // 计算税费
            $product->tax_amount;
            // 单品优惠
            $product->discount_amount;


            // 实付金额 完成支付时
            $product->payment_amount = $product->payment_amount ?? 0;
            // 佣金
            $product->commission_amount = $product->commission_amount ?? 0;

            // 单商品应付金额  = 商品金额 - 单品优惠 + 税费
            $product->payable_amount = bcsub(
                bcadd($product->product_amount, $product->tax_amount, 2), $product->discount_amount, 2
            );
        }
    }

    protected function calculateOrderAmount() : void
    {

        // 商品统计

        // 商品金额
        $this->product_amount = $this->products->reduce(function ($sum, $product) {
            return bcadd($sum, $product->product_amount, 2);
        }, 0);

        // 商品成本
        $this->cost_amount = $this->products->reduce(function ($sum, $product) {
            return bcadd($sum, $product->cost_amount, 2);
        }, 0);
        // 商品总税费
        $this->tax_amount = $this->products->reduce(function ($sum, $product) {
            return bcadd($sum, $product->tax_amount, 2);
        }, 0);
        // 商品总佣金
        $this->commission_amount = $this->products->reduce(function ($sum, $product) {
            return bcadd($sum, $product->commission_amount, 2);
        }, 0);


        // | ------------------------------------------------

        // 商品应付汇总
        $this->product_payable_amount = $this->products->reduce(function ($sum, $product) {
            return bcadd($sum, $product->payable_amount, 2);
        }, 0);
        // 邮费
        $this->freight_amount;
        // 订单优惠
        $this->discount_amount;
        // 订单服务费
        $this->service_amount;

        // 订单应付金额 = 商品总应付金额 + 邮费 + 订单服务费 - 优惠
        $this->payable_amount = bcsub(
            bcadd(bcadd($this->product_payable_amount, $this->freight_amount, 2), $this->service_amount, 2),
            $this->discount_amount,
            2
        );

    }

    /**
     * 计算分摊优惠
     * @return void
     */
    protected function calculateDivideDiscountAmount() : void
    {
        $order = $this;
        $order->discount_amount;

        // 对商品进行排序 从小到大
        $products     = $order->products->sortBy('product_amount')->values();
        $productCount = count($products);
        // 剩余优惠金额
        $discountAmountSurplus = $this->discount_amount;
        /**
         * @var $product OrderProduct
         */
        foreach ($products as $key => $product) {

            $product->divided_discount_amount = 0;
            if ($key === $productCount - 1) {
                // 最后一个子商品单  分摊优惠  = 剩余优惠
                $product->divided_discount_amount = $discountAmountSurplus;
            } else {
                if (bccomp($product->payable_amount, 0, 2) !== 0) {
                    $product->divided_discount_amount = bcmul($this->discount_amount,
                                                              bcdiv($product->payable_amount, $this->product_payable_amount, 4), 2);
                } else {
                    $product->divided_discount_amount = 0;
                }
            }
            $discountAmountSurplus           = bcsub($discountAmountSurplus, $product->divided_discount_amount, 2);
            $product->divided_payment_amount = bcsub($product->payable_amount, $product->divided_discount_amount, 2);

        }


    }


    /**
     * 添加或更新交易双方的备注信息
     *
     * 此函数用于在订单或订单产品中添加或更新特定交易双方的备注信息
     * 它根据提供的交易双方类型动态确定存储备注信息的字段
     * 如果提供了订单产品ID，则备注信息将被添加到该特定订单产品；
     * 否则，将备注信息添加到订单本身此函数演示了如何动态处理数据字段基于枚举值，
     * 以及如何根据条件逻辑确定操作的对象（订单或订单产品）
     *
     * @param TradePartyEnums $tradeParty 交易双方类型，用于确定备注信息字段
     * @param string|null $remarks 备注信息文本，要添加或更新的内容
     * @param int|null $orderProductId 订单产品ID，指定特定的订单产品添加备注信息
     * @param bool $isAppend 是否追加备注信息，如果为true，则在现有备注信息后追加新内容
     * @return void
     */
    public function remarks(TradePartyEnums $tradeParty, string $remarks = null, ?int $orderProductId = null, bool $isAppend = false) : void
    {
        // 根据交易双方类型动态确定备注信息字段名
        $field = $tradeParty->value . '_remarks';

        // 根据是否提供订单产品ID，确定操作的对象
        if ($orderProductId) {
            // 如果提供了订单产品ID，获取对应的订单产品实例
            $model = $this->products->where('id', $orderProductId)->firstOrFail();
        } else {
            // 如果未提供订单产品ID，操作订单本身
            $model = $this;
        }
        // 在确定的对象上添加或更新备注信息
        if ($isAppend && blank($model->info->{$field})) {
            $isAppend = false;
        }
        if ($isAppend) {
            $model->info->{$field} .= "\n\r" . $remarks;
        } else {
            $model->info->{$field} = $remarks;
        }

    }


    public function message(TradePartyEnums $tradeParty, string $message = null, ?int $orderProductId = null, bool $isAppend = false) : void
    {

        $field = $tradeParty->value . '_message';


        if ($orderProductId) {

            $model = $this->products->where('id', $orderProductId)->firstOrFail();
        } else {

            $model = $this;
        }

        if ($isAppend && blank($model->info->{$field})) {
            $isAppend = false;
        }
        if ($isAppend) {
            $model->info->{$field} .= "\n\r" . $message;
        } else {
            $model->info->{$field} = $message;
        }

    }

    public function setSellerCustomStatus(string $sellerCustomStatus, ?int $orderProductId = null) : void
    {
        if ($orderProductId) {
            $model = $this->products->where('id', $orderProductId)->firstOrFail();
        } else {
            $model = $this;
        }
        $model->seller_custom_status = $sellerCustomStatus;


    }


    /**
     * @param TradePartyEnums $tradeParty
     * @param bool $isHidden
     *
     * @return void
     * @throws OrderException
     */
    public function hiddenOrder(TradePartyEnums $tradeParty, bool $isHidden = true) : void
    {

        switch ($tradeParty) {
            case TradePartyEnums::SELLER:
                $this->is_seller_delete = $isHidden;
                break;
            case TradePartyEnums::BUYER:
                $this->is_buyer_delete = $isHidden;
                break;
            default:
                throw new OrderException('交易方不支持');
                break;
        }

    }

}
