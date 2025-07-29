<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Static_;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;

/**
 * @property UserInterface $user
 * @property Money $price
 * @property Money $total_amount
 */
class PointsExchangeOrder extends Model implements OperatorInterface, OwnerInterface, UniqueNoInterface
{

    protected static string $uniqueNoKey = 'points_order_no';

    protected static string $uniqueNoPrefix = 'PO';

    use HasUniqueNo;
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'owner',
        'user',
        'order_no',
        'outer_order_no',
        'point_product_id',
        'product_type',
        'product_id',
        'product_title',
        'point',
        'price_currency',
        'price_amount',
        'quantity',
        'payment_mode',
        'payment_status',
        'status',
        'exchange_time',
    ];



    public function newInstance($attributes = [], $exists = false)
    {
        $instance =  parent::newInstance($attributes, $exists);
        if(!$instance->exists){
            $instance->setUniqueNo();
        }

        return  $instance;
    }

    protected function casts(): array
    {
        return [
            'point' => 'integer',
            'total_point' => 'integer',
            'quantity' => 'integer',
            'payment_mode' => PointsProductPaymentModeEnum::class,
            'status' => PointsExchangeOrderStatusEnum::class,
            'exchange_time' => 'datetime',
            'user' => UserInterfaceCast::class,
            'price' => MoneyCast::class,
            'total_amount' => MoneyCast::class,
        ];
    }


    protected static function boot(): void
    {
        parent::boot();

        // 生命周期钩子
        static::creating(function ($model) {
            // 创建时的业务逻辑
            if (empty($model->exchange_time)) {
                $model->exchange_time = now();
            }
        });

        static::saving(function ($model) {
            // 保存时的业务逻辑
        });

        static::deleting(function ($model) {
            // 删除时的业务逻辑
        });
    }


    public function buildUniqueNoFactors(): array
    {
        return [
            $this->owner_id,
            $this->user_id,
        ];
    }


    /**
     * 获取总价值（积分转换为现金）
     */
    public function getTotalValue(float $pointsRate = 0.01): float
    {
        $pointsMoney = $this->point * $pointsRate;
        return $pointsMoney + $this->price_amount;
    }

    /**
     * 检查是否为混合支付
     */
    public function isMixedPayment(): bool
    {
        return $this->payment_mode === PointsProductPaymentModeEnum::MIXED;
    }

    /**
     * 检查是否为纯积分支付
     */
    public function isPointsOnlyPayment(): bool
    {
        return $this->payment_mode === PointsProductPaymentModeEnum::POINTS;
    }


    /**
     * 更新订单状态
     */
    public function updateStatus(PointsExchangeOrderStatusEnum $status): void
    {
        $this->status = $status;
    }

    /**
     * 检查是否为已兑换状态
     */
    public function isExchanged(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::EXCHANGED;
    }

    /**
     * 检查是否为订单已创建状态
     */
    public function isOrderCreated(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_CREATED;
    }

    /**
     * 检查是否为订单已支付状态
     */
    public function isOrderPaid(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_PAID;
    }

    /**
     * 检查是否为订单已接单状态
     */
    public function isOrderAccepted(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_ACCEPTED;
    }

    /**
     * 检查是否为订单已发货状态
     */
    public function isOrderShipped(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_SHIPPED;
    }

    /**
     * 检查是否为订单已完成状态
     */
    public function isOrderFinished(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_FINISHED;
    }

    /**
     * 检查是否为订单已取消状态
     */
    public function isOrderCanceled(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_CANCELED;
    }

    /**
     * 检查是否可以取消订单
     */
    public function canCancel(): bool
    {
        return in_array($this->status, [
            PointsExchangeOrderStatusEnum::EXCHANGED,
            PointsExchangeOrderStatusEnum::ORDER_CREATED,
        ]);
    }

    /**
     * 检查是否可以支付
     */
    public function canPay(): bool
    {
        return in_array($this->status, [
            PointsExchangeOrderStatusEnum::EXCHANGED,
            PointsExchangeOrderStatusEnum::ORDER_CREATED,
        ]);
    }

    /**
     * 检查是否可以发货
     */
    public function canShip(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_ACCEPTED;
    }

    /**
     * 检查是否可以完成订单
     */
    public function canFinish(): bool
    {
        return $this->status === PointsExchangeOrderStatusEnum::ORDER_SHIPPED;
    }

    /**
     * 设置支付状态
     */
    public function setPaymentStatus(string $paymentStatus): void
    {
        $this->payment_status = $paymentStatus;
    }

    /**
     * 检查是否已支付
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * 检查是否支付失败
     */
    public function isPaymentFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * 检查是否待支付
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * 获取状态标签
     */
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

    /**
     * 获取状态颜色
     */
    public function getStatusColor(): string
    {
        return $this->status->color();
    }

    /**
     * 获取支付模式标签
     */
    public function getPaymentModeLabel(): string
    {
        return $this->payment_mode->label();
    }

    /**
     * 获取支付模式颜色
     */
    public function getPaymentModeColor(): string
    {
        return $this->payment_mode->color();
    }

    /**
     * 关联积分商品
     */
    public function pointProduct()
    {
        return $this->belongsTo(PointsProduct::class, 'point_product_id');
    }
} 