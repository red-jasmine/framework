<?php

namespace RedJasmine\Order\Domain\Models;

use RedJasmine\Money\Data\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Helpers\HasSerialNumber;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Extensions\OrderProductExtension;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;
use Spatie\LaravelData\WithData;

/**
 * @property string $order_product_no
 * @property Money $cost_price
 * @property Money $total_price
 * @property Money $product_amount
 * @property Money $service_amount
 * @property Money $freight_amount
 * @property Money $refund_amount
 * @property Money $payable_amount
 */
class OrderProduct extends Model implements UniqueNoInterface
{
    public static string $uniqueNoPrefix = 'DP';
    public static string $uniqueNoKey    = 'order_product_no';
    use HasUniqueNo;

    use HasSerialNumber;

    use HasSnowflakeId;


    use WithData;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;

    use HasCommonAttributes;

    public $incrementing = false;

    public bool $withTradePartiesNickname = false;


    public function casts() : array
    {
        return array_merge([
            'order_product_type' => ProductTypeEnum::class,
            'shipping_type'      => ShippingTypeEnum::class,
            'order_status'       => OrderStatusEnum::class,
            'shipping_status'    => ShippingStatusEnum::class,
            'payment_status'     => PaymentStatusEnum::class,
            'refund_status'      => RefundStatusEnum::class,
            'created_time'       => 'datetime',
            'payment_time'       => 'datetime',
            'close_time'         => 'datetime',
            'shipping_time'      => 'datetime',
            'collect_time'       => 'datetime',
            'dispatch_time'      => 'datetime',
            'signed_time'        => 'datetime',
            'confirm_time'       => 'datetime',
            'refund_time'        => 'datetime',
            'rate_time'          => 'datetime',


            'commission_amount'        => MoneyCast::class,
            'seller_discount_amount'   => MoneyCast::class,
            'platform_discount_amount' => MoneyCast::class,
            'platform_service_amount'  => MoneyCast::class,
            'receivable_amount'        => MoneyCast::class,
            'received_amount'          => MoneyCast::class,

        ], $this->getCommonAttributesCast());
    }

    protected $fillable = [
        'shipping_type',
        'product_type',
        'product_id',
        'sku_id',
        'quantity',
        'price',
        'buyer',
        'seller',
        'biz',
        'currency',
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
        if (!$instance->exists && !empty($attributes)) {
            $instance->setUniqueNo();
        }
        return $instance;
    }


    public function buildUniqueNoFactors() : array
    {
        return [
            $this->biz,
            $this->seller_id,
            $this->buyer_id
        ];
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
        return $this->hasMany(Refund::class, 'order_product_id', 'id');
    }


    public function cardKeys() : HasMany
    {
        return $this->hasMany(OrderCardKey::class, 'entity_id', 'order_product_no');
    }

    public function addCardKey(OrderCardKey $cardKey) : void
    {
        $cardKey->seller      = $this->seller;
        $cardKey->buyer       = $this->buyer;
        $cardKey->order_no    = $this->order_no;
        $cardKey->biz      = $this->biz;
        $cardKey->entity_id   = $this->order_product_no;
        $cardKey->entity_type = EntityTypeEnum::ORDER_PRODUCT;

        $this->cardKeys->add($cardKey);
    }

    /**
     * 是否为有效单
     * @return bool
     */
    public function isEffective() : bool
    {
        // 没有全款退
        if ($this->payment_amount->subtract($this->refund_amount)->isZero() || $this->payment_amount->subtract($this->refund_amount)->isNegative()) {
            return false;
        }
        return true;
    }


    /**
     * 最大退款金额
     * @return Money
     */
    public function maxRefundProductAmount() : Money
    {
        // 分摊后应付金额 - 退款金额 - 分摊邮费 TODO 根据
        return $this->payable_amount
            ->subtract($this->refund_amount)
            ->subtract($this->freight_amount);
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
            if (in_array($this->shipping_status, [ShippingStatusEnum::PARTIAL, ShippingStatusEnum::SHIPPED],
                true)) {
                $allowApplyRefundTypes[] = RefundTypeEnum::RETURN_GOODS_REFUND;
            }
        }
        // 换货 只有物流发货才支持换货 TODO
        if (in_array($this->shipping_status, [ShippingStatusEnum::PARTIAL, ShippingStatusEnum::SHIPPED], true)
            && $this->isAllowAfterSaleService(RefundTypeEnum::EXCHANGE)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::EXCHANGE;
        }
        // 保修
        if (in_array($this->shipping_status, [ShippingStatusEnum::PARTIAL, ShippingStatusEnum::SHIPPED], true)
            && $this->isAllowAfterSaleService(RefundTypeEnum::WARRANTY)) {
            $allowApplyRefundTypes[] = RefundTypeEnum::WARRANTY;
        }

        // TODO 最长时间
        if (in_array($this->shipping_status, [ShippingStatusEnum::PARTIAL, ShippingStatusEnum::SHIPPED], true)) {
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
