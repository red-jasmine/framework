<?php


namespace RedJasmine\Order\Domain\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Events\OrderAcceptEvent;
use RedJasmine\Order\Domain\Events\OrderCanceledEvent;
use RedJasmine\Order\Domain\Events\OrderClosedEvent;
use RedJasmine\Order\Domain\Events\OrderConfirmedEvent;
use RedJasmine\Order\Domain\Events\OrderCreatedEvent;
use RedJasmine\Order\Domain\Events\OrderCustomStatusChangedEvent;
use RedJasmine\Order\Domain\Events\OrderFinishedEvent;
use RedJasmine\Order\Domain\Events\OrderPaidEvent;
use RedJasmine\Order\Domain\Events\OrderPayingEvent;
use RedJasmine\Order\Domain\Events\OrderProgressEvent;
use RedJasmine\Order\Domain\Events\OrderRejectEvent;
use RedJasmine\Order\Domain\Events\OrderShippedEvent;
use RedJasmine\Order\Domain\Events\OrderShippingEvent;
use RedJasmine\Order\Domain\Events\OrderStarChangedEvent;
use RedJasmine\Order\Domain\Events\OrderUrgeEvent;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Generator\OrderNoGenerator;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;
use RedJasmine\Order\Domain\Models\Extensions\OrderExtension;
use RedJasmine\Order\Domain\Models\Features\HasStar;
use RedJasmine\Order\Domain\Models\Features\HasUrge;
use RedJasmine\Order\Domain\Services\OrderRefundService;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\MoneyOldCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class Order extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    use HasUrge;

    use HasStar;

    public bool $withTradePartiesNickname = true;

    public $incrementing = false;

    protected $fillable = [
        'app_id',
        'buyer_id',
        'seller_id'
    ];

    protected $dispatchesEvents = [
        'created'             => OrderCreatedEvent::class,
        'canceled'            => OrderCanceledEvent::class,
        'paying'              => OrderPayingEvent::class,
        'paid'                => OrderPaidEvent::class,
        'accept'              => OrderAcceptEvent::class,
        'reject'              => OrderRejectEvent::class,
        'shipping'            => OrderShippingEvent::class,
        'shipped'             => OrderShippedEvent::class,
        'progress'            => OrderProgressEvent::class,
        'finished'            => OrderFinishedEvent::class,
        'confirmed'           => OrderConfirmedEvent::class,
        'closed'              => OrderClosedEvent::class,
        'customStatusChanged' => OrderCustomStatusChangedEvent::class,
        'starChanged'         => OrderStarChangedEvent::class,
        'urge'                => OrderUrgeEvent::class,
    ];
    protected $observables      = [
        'paying',
        'paid',
        'accept',
        'reject',
        'shipping',
        'shipped',
        'progress',
        'confirmed',
        'canceled',
        'closed',
        'customStatusChanged',
        'starChanged',
        'urge',

    ];

    public function newInstance($attributes = [], $exists = false) : Order
    {

        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->generateNo();
            $extension     = OrderExtension::make();
            $extension->id = $instance->id;
            $instance->setRelation('extension', $extension);
            $instance->setRelation('products', Collection::make());
            $instance->setRelation('payments', Collection::make());
            $instance->setRelation('address', null);
        }
        return $instance;
    }

    protected function generateNo() : void
    {
        if (!$this->order_no && isset($this->app_id)) {
            $this->order_no = app(OrderNoGenerator::class)->generator(
                [
                    'app_id'    => $this->app_id,
                    'seller_id' => $this->seller_id,
                    'buyer_id'  => $this->buyer_id,
                ]
            );
        }

    }

    public function casts() : array
    {
        return [
            'order_type'             => OrderTypeEnum::class,
            'shipping_type'          => ShippingTypeEnum::class,
            'order_status'           => OrderStatusEnum::class,
            'accept_status'          => AcceptStatusEnum::class,
            'payment_status'         => PaymentStatusEnum::class,
            'shipping_status'        => ShippingStatusEnum::class,
            'created_time'           => 'datetime',
            'payment_time'           => 'datetime',
            'accept_time'            => 'datetime',
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
            'freight_amount'         => MoneyOldCast::class.':'.'freight_amount,currency',
            'discount_amount'        => MoneyOldCast::class.':'.'discount_amount,currency',
            'product_payable_amount' => MoneyOldCast::class.':'.'product_payable_amount,currency',
            'payable_amount'         => MoneyOldCast::class.':'.'payable_amount,currency',
            'payment_amount'         => MoneyOldCast::class.':'.'payment_amount,currency',
            'refund_amount'          => MoneyOldCast::class.':'.'refund_amount,currency',
            'commission_amount'      => MoneyOldCast::class.':'.'commission_amount,currency',
            'cost_amount'            => MoneyOldCast::class.':'.'cost_amount,currency',
            'tax_amount'             => MoneyOldCast::class.':'.'tax_amount,currency',
            'product_amount'         => MoneyOldCast::class.':'.'product_amount,currency',
        ];
    }




    public function extension() : HasOne
    {
        return $this->hasOne(OrderExtension::class, 'id', 'id');
    }


    public function products() : HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_no', 'order_no');
    }

    public function address() : HasOne
    {
        return $this->hasOne(OrderAddress::class, 'id', 'id');
    }

    public function logistics() : MorphMany
    {
        return $this->morphMany(OrderLogistics::class, 'entity');
    }

    public function payments() : HasMany
    {
        return $this->hasMany(OrderPayment::class, 'order_no', 'order_no');
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
        $orderProduct->app_id         = $this->app_id;
        $orderProduct->shipping_type  = $this->shipping_type;
        $orderProduct->order_no       = $this->order_no;
        $orderProduct->buyer          = $this->buyer;
        $orderProduct->seller         = $this->seller;
        $orderProduct->progress_total = (int) bcmul($orderProduct->quantity, $orderProduct->unit_quantity, 0);
        $orderProduct->created_time   = now();

        $this->products->add($orderProduct);
        return $this;
    }


    public function setAddress(OrderAddress $orderAddress) : static
    {
        $orderAddress->id       = $this->id;
        $orderAddress->order_no = $this->order_no;

        $this->setRelation('address', $orderAddress);
        return $this;
    }

    public function addLogistics(OrderLogistics $logistics) : void
    {
        $logistics->app_id      = $this->app_id;
        $logistics->entity_type = EntityTypeEnum::ORDER;
        $logistics->entity_id   = $this->id;
        $logistics->order_no    = $this->order_no;
        $logistics->seller_type = $this->seller_type;
        $logistics->seller_id   = $this->seller_id;
        $logistics->buyer_type  = $this->buyer_type;
        $logistics->buyer_id    = $this->buyer_id;
        $this->logistics->add($logistics);
    }


    public function isEffective() : bool
    {
        if (bcsub($this->payment_amount, $this->refund_amount, 2) <= 0) {
            return false;
        }
        return true;
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
                    [
                        null,
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

        // 虚拟商品那么就立即签收
        if (($this->shipping_status === ShippingStatusEnum::SHIPPED) && $this->shipping_type === ShippingTypeEnum::DUMMY) {
            $this->signed_time = now();
        }

        $this->fireModelEvent($event, false);

    }

    /**
     * @param  string|null  $reason
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
            [PaymentStatusEnum::PAID, PaymentStatusEnum::PART_PAY, PaymentStatusEnum::NO_PAYMENT,], true)) {
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
     * @return void
     * @throws OrderException
     */
    public function accept() : void
    {
        // 什么情况下可以接受

        if ($this->order_status !== OrderStatusEnum::WAIT_SELLER_ACCEPT) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        $this->accept_status = AcceptStatusEnum::ACCEPTED;
        $this->accept_time   = now();

        $this->fireModelEvent('accept', false);
    }

    /**
     * @param  string|null  $reason
     *
     * @return void
     * @throws OrderException
     */
    public function reject(?string $reason = null) : void
    {
        if ($this->order_status !== OrderStatusEnum::WAIT_SELLER_ACCEPT) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        if ($this->accept_status !== AcceptStatusEnum::WAIT_ACCEPT) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        $this->accept_status = AcceptStatusEnum::REJECTED;
        $this->close_time    = now();
        $this->cancel_reason = $reason;

        $this->fireModelEvent('reject', false);

        // 如果已支付 主动退款

    }

    /**
     * 发起支付
     *
     * @param  OrderPayment  $orderPayment
     *
     * @return void
     * @throws OrderException
     */
    public function paying(OrderPayment $orderPayment) : void
    {
        if (!in_array($this->payment_status, [PaymentStatusEnum::WAIT_PAY, null], true)) {
            throw  OrderException::newFromCodes(OrderException::PAYMENT_STATUS_NOT_ALLOW);
        }
        // 添加支付单
        $orderPayment->app_id      = $this->app_id;
        $orderPayment->order_no    = $this->order_no;
        $orderPayment->buyer       = $this->buyer;
        $orderPayment->seller      = $this->seller;
        $orderPayment->status      = PaymentStatusEnum::PAYING;
        $orderPayment->entity_type = EntityTypeEnum::ORDER;
        $orderPayment->entity_id   = $this->id;
        $this->addPayment($orderPayment);
        // 设置为支付中
        if (in_array($this->payment_status, [PaymentStatusEnum::WAIT_PAY, null], true)) {
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
     * @param  OrderPayment  $orderPayment
     *
     * @return void
     * @throws OrderException
     */
    public function paid(OrderPayment $orderPayment) : void
    {
        if (!in_array($this->payment_status, [
            null, PaymentStatusEnum::WAIT_PAY, PaymentStatusEnum::PAYING, PaymentStatusEnum::PART_PAY
        ], true)) {
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
     * @param  int|null  $orderProductId
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

    public function close() : void
    {
        $this->order_status = OrderStatusEnum::CLOSED;
        $this->close_time   = now();
        $this->fireModelEvent('close', false);
    }

    /**
     * @param  int  $orderProductId
     * @param  int  $progress
     * @param  bool  $isAppend
     * @param  bool  $isAllowLess
     *
     * @return int 最新的进度
     * @throws OrderException
     */
    public function setProductProgress(
        int $orderProductId,
        int $progress,
        bool $isAppend = false,
        bool $isAllowLess = false
    ) : int {

        $orderProduct = $this->products->where('id', $orderProductId)->firstOrFail();

        // 判断发货方式是否不支持设置进度
        if ($orderProduct->shipping_type === ShippingTypeEnum::CARD_KEY) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW_SET_PROGRESS,
                '进度不允许小于之前的值');
        }

        $oldProgress = (int) $orderProduct->progress;
        $newProgress = $isAppend ? ((int) bcadd($oldProgress, $progress, 0)) : $progress;
        if ($oldProgress === $newProgress) {
            return $newProgress;
        }
        //判断是否允许更小
        if ($isAllowLess === false && bccomp($newProgress, $oldProgress, 0) < 0) {
            throw OrderException::newFromCodes(OrderException::PROGRESS_NOT_ALLOW_LESS, '进度不允许小于之前的值');
        }

        $orderProduct->progress = $newProgress;

        $this->fireModelEvent('progress', false);
        return (int) $orderProduct->progress;
    }

    public function refunds() : HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_no', 'order_no');
    }

    /**
     * @param  OrderRefund  $orderRefund
     *
     * @return OrderRefund
     * @throws RefundException
     */
    public function createRefund(OrderRefund $orderRefund) : OrderRefund
    {
        return app(OrderRefundService::class)->create($this, $orderRefund);
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
            $product->cost_amount = bcmul($product->quantity, $product->cost_price, 2);

            // 商品总金额   < 0 TODO 验证金额
            $product->product_amount = bcmul($product->quantity, $product->price, 2);
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
                bcadd($product->product_amount

                    , $product->tax_amount, 2), $product->discount_amount, 2
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


        // 订单应付金额 = 商品总应付金额 + 邮费  - 优惠
        $this->payable_amount = bcsub(
            bcadd($this->product_payable_amount, $this->freight_amount, 2),
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


    public function isAllowShipping() : bool
    {
        if ($this->order_status === OrderStatusEnum::WAIT_SELLER_SEND_GOODS) {
            return true;
        }

        return false;
    }

    public function isAccepting() : bool
    {
        if ($this->order_status !== OrderStatusEnum::WAIT_SELLER_ACCEPT) {
            return false;
        }

        if (in_array($this->accept_status, [
            AcceptStatusEnum::WAIT_ACCEPT,
            AcceptStatusEnum::REJECTED,
        ], true)) {
            return true;
        }


        return false;
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
     * @param  TradePartyEnums  $tradeParty  交易双方类型，用于确定备注信息字段
     * @param  string|null  $remarks  备注信息文本，要添加或更新的内容
     * @param  int|null  $orderProductId  订单产品ID，指定特定的订单产品添加备注信息
     * @param  bool  $isAppend  是否追加备注信息，如果为true，则在现有备注信息后追加新内容
     *
     * @return void
     */
    public function remarks(
        TradePartyEnums $tradeParty,
        string $remarks = null,
        ?int $orderProductId = null,
        bool $isAppend = false
    ) : void {
        // 根据交易双方类型动态确定备注信息字段名
        $field = $tradeParty->value.'_remarks';

        // 根据是否提供订单产品ID，确定操作的对象
        if ($orderProductId) {
            // 如果提供了订单产品ID，获取对应的订单产品实例
            $model = $this->products->where('id', $orderProductId)->firstOrFail();
        } else {
            // 如果未提供订单产品ID，操作订单本身
            $model = $this;
        }
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


    public function message(
        TradePartyEnums $tradeParty,
        string $message = null,
        ?int $orderProductId = null,
        bool $isAppend = false
    ) : void {

        $field = $tradeParty->value.'_message';


        if ($orderProductId) {

            $model = $this->products->where('id', $orderProductId)->firstOrFail();
        } else {

            $model = $this;
        }

        if ($isAppend && blank($model->extension->{$field})) {
            $isAppend = false;
        }
        if ($isAppend) {
            $model->extension->{$field} .= "\n\r".$message;
        } else {
            $model->extension->{$field} = $message;
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

        $this->fireModelEvent('customStatusChanged', false);

    }


    /**
     * @param  TradePartyEnums  $tradeParty
     * @param  bool  $isHidden
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

    public function isRefundFreightAmount() : bool
    {
        $excludeFreightAmount = bcsub($this->payment_amount, $this->freight_amount, 2);
        if (bcsub($this->refund_amount, $excludeFreightAmount, 2) >= 0) {
            return true;
        }
        return false;
    }


    // |---------------scope----------------------------

    public function scopeOnWaitBuyerPay(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::WAIT_BUYER_PAY);
    }

    public function scopeOnWaitSellerAccept(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::WAIT_SELLER_ACCEPT)
                       ->where('accept_status', AcceptStatusEnum::WAIT_ACCEPT);
    }

    public function scopeOnWaitSellerSendGoods(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::WAIT_SELLER_SEND_GOODS);
    }

    public function scopeOnWaitBuyerConfirmGoods(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS);
    }

    public function scopeOnFinished(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::FINISHED);
    }

    public function scopeOnCancel(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::CANCEL);
    }

    public function scopeOnClosed(Builder $builder) : Builder
    {
        return $builder->where('order_status', OrderStatusEnum::CLOSED);
    }

    public function scopeOnCancelClosed(Builder $builder) : Builder
    {
        return $builder->whereIn('order_status', [OrderStatusEnum::CLOSED, OrderStatusEnum::CANCEL]);
    }

}
