<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Contracts\UserInterface;
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
        'status',
        'exchange_time',
    ];


    public function newInstance($attributes = [], $exists = false) : PointsExchangeOrder
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists && !empty($attributes)) {
            $instance->setUniqueNo();
        }

        return $instance;
    }

    protected function casts() : array
    {
        return [
            'point'         => 'integer',
            'total_point'   => 'integer',
            'quantity'      => 'integer',
            'payment_mode'  => PointsProductPaymentModeEnum::class,
            'status'        => PointsExchangeOrderStatusEnum::class,
            'exchange_time' => 'datetime',
            'user'          => UserInterfaceCast::class,
            'price'         => MoneyCast::class,
            'total_amount'  => MoneyCast::class,
        ];
    }


    protected static function boot() : void
    {
        parent::boot();

        // 生命周期钩子
        static::creating(function ($model) {
            // 创建时的业务逻辑
            if (empty($model->exchange_time)) {
                $model->exchange_time = now();
            }
        });


    }


    public function buildUniqueNoFactors() : array
    {
        return [
            $this->owner_id,
            $this->user_id,
        ];
    }


    /**
     * 更新订单状态
     */
    public function updateStatus(PointsExchangeOrderStatusEnum $status) : void
    {
        $this->status = $status;
    }


    public function paid() : void
    {
        $this->status = PointsExchangeOrderStatusEnum::PAID;

    }

    /**
     * 关联积分商品
     */
    public function pointProduct()
    {
        return $this->belongsTo(PointsProduct::class, 'point_product_id');
    }

    public function isPaying() : bool
    {
        return $this->status == PointsExchangeOrderStatusEnum::PAYING;
    }

    /**
     * 是否需要支付
     * @return bool
     */
    public function canPaymentMoney() : bool
    {
        return bccomp($this->total_amount_amount, 0, 2) > 0;
    }
} 