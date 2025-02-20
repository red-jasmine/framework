<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Extensions\OrderProductExtension;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use Spatie\LaravelData\WithData;

class OrderProduct extends Model
{

    use HasSnowflakeId;


    use WithData;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;


    public $incrementing = false;

    public bool $withTradePartiesNickname = false;

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix', 'jasmine_').'order_products';
    }

    protected $casts = [
        'order_product_type'      => ProductTypeEnum::class,
        'shipping_type'           => ShippingTypeEnum::class,
        'order_status'            => OrderStatusEnum::class,
        'shipping_status'         => ShippingStatusEnum::class,
        'payment_status'          => PaymentStatusEnum::class,
        'refund_status'           => RefundStatusEnum::class,
        'created_time'            => 'datetime',
        'payment_time'            => 'datetime',
        'close_time'              => 'datetime',
        'shipping_time'           => 'datetime',
        'collect_time'            => 'datetime',
        'dispatch_time'           => 'datetime',
        'signed_time'             => 'datetime',
        'confirm_time'            => 'datetime',
        'refund_time'             => 'datetime',
        'rate_time'               => 'datetime',
        'price'                   => AmountCastTransformer::class,
        'cost_price'              => AmountCastTransformer::class,
        'tax_amount'              => AmountCastTransformer::class,
        'product_amount'          => AmountCastTransformer::class,
        'payable_amount'          => AmountCastTransformer::class,
        'payment_amount'          => AmountCastTransformer::class,
        'refund_amount'           => AmountCastTransformer::class,
        'discount_amount'         => AmountCastTransformer::class,
        'commission_amount'       => AmountCastTransformer::class,
        'divided_discount_amount' => AmountCastTransformer::class,
        'divided_payment_amount'  => AmountCastTransformer::class,
    ];

    protected $fillable = [
        'shipping_type',
        'product_type',
        'product_id',
        'sku_id',
        'quantity',
        'price',
    ];


    public function newInstance($attributes = [], $exists = false) : OrderProduct
    {

        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $extension     = OrderProductExtension::make();
            $extension->id = $instance->id;
            $instance->setRelation('extension', $extension);

        }
        return $instance;
    }


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_no', 'order_no');
    }


    public function extension() : HasOne
    {
        return $this->hasOne(OrderProductExtension::class, 'id', 'id');
    }


    public function refunds() : HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_product_id', 'id');
    }


    public function cardKeys() : HasMany
    {
        return $this->hasMany(OrderCardKey::class, 'order_product_id', 'id');
    }

    public function addCardKey(OrderCardKey $cardKey) : void
    {
        $cardKey->seller = $this->seller;
        $cardKey->buyer  = $this->buyer;

        $cardKey->order_no = $this->order_no;
        $cardKey->app_id   = $this->app_id;
        $this->progress    += $cardKey->quantity;
        $this->cardKeys->add($cardKey);
    }

    /**
     * 是否为有效单
     * @return bool
     */
    public function isEffective() : bool
    {
        // 没有全款退
        if (bcsub($this->divided_payment_amount, $this->refund_amount, 2) <= 0) {
            return false;
        }
        return true;
    }


    /**
     * 最大退款金额
     * @return string
     */
    public function maxRefundAmount() : string
    {
        return bcsub($this->divided_payment_amount, $this->refund_amount, 2);
    }


    /**
     * 允许的售后类型
     * @return array
     */
    public function allowRefundTypes() : array
    {
        // 有效单判断
        if ($this->isEffective() === false) {
            return [];
        }
        $allowApplyRefundTypes = [];


        // 退款
        if ($this->isAllowAfterSaleService(RefundTypeEnum::REFUND)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::REFUND;
            if (in_array($this->shipping_status, [ShippingStatusEnum::PART_SHIPPED, ShippingStatusEnum::SHIPPED],
                true)) {
                $allowApplyRefundTypes[] = RefundTypeEnum::RETURN_GOODS_REFUND;
            }
        }
        // 换货 只有物流发货才支持换货 TODO
        if (in_array($this->shipping_status, [ShippingStatusEnum::PART_SHIPPED, ShippingStatusEnum::SHIPPED], true)
            && $this->isAllowAfterSaleService(RefundTypeEnum::EXCHANGE)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::EXCHANGE;
        }
        // 保修
        if (in_array($this->shipping_status, [ShippingStatusEnum::PART_SHIPPED, ShippingStatusEnum::SHIPPED], true)
            && $this->isAllowAfterSaleService(RefundTypeEnum::WARRANTY)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::WARRANTY;
        }

        // TODO 最长时间
        if (in_array($this->shipping_status, [ShippingStatusEnum::PART_SHIPPED, ShippingStatusEnum::SHIPPED], true)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::RESHIPMENT;
        }


        return $allowApplyRefundTypes;
    }


    public function isAllowAfterSaleService(RefundTypeEnum $refundType) : bool
    {
        // 获取售后服务
        $afterSalesServices = AfterSalesService::collect($this->extension->after_sales_services);
        /**
         * @var AfterSalesService $afterSalesService
         */

        $afterSalesService = collect($afterSalesServices)->filter(function ($item) use ($refundType) {
            return $item->refundType === $refundType;
        })->first();

        if (!$afterSalesService) {
            return false;
        }
        if ($afterSalesService->allowStage === OrderAfterSaleServiceAllowStageEnum::NEVER) {
            return false;
        }
        // 判断状态 TODO
        $lastTime = now();
        // 计算剩余时间
        switch ($afterSalesService->allowStage) {
            case OrderAfterSaleServiceAllowStageEnum::PAYED:

                $lastTime = $this->payment_time->add($afterSalesService->getAddValue());

                break;
            case OrderAfterSaleServiceAllowStageEnum::SHIPPED:
            case OrderAfterSaleServiceAllowStageEnum::SHIPPING:

                $lastTime = ($this->shipping_time ?? now())->add($afterSalesService->getAddValue());

                break;
            case OrderAfterSaleServiceAllowStageEnum::SIGNED:
                $lastTime = ($this->signed_time ?? now())->add($afterSalesService->getAddValue());
                break;
            case OrderAfterSaleServiceAllowStageEnum::COMPLETED:
                $lastTime = ($this->confirm_time ?? now())->add($afterSalesService->getAddValue());

        }

        if (now()->diffInRealSeconds($lastTime, false) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function isAllowSetProgress()
    {
        if ($this->shipping_type === ShippingTypeEnum::DUMMY) {
            return true;
        }

        return false;

    }


}
