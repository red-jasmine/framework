<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\PointsMall\Domain\Models\ValueObjects\ExchangeLimit;
use RedJasmine\PointsMall\Domain\Models\ValueObjects\PaymentInfo;
use RedJasmine\PointsMall\Domain\Models\ValueObjects\StockInfo;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property float|int $point
 * @property Money $price
 * @property PointsProductPaymentModeEnum $payment_mode
 */
class PointsProduct extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'title',
        'description',
        'image',
        'point',
        'price_currency',
        'price_amount',
        'payment_mode',
        'stock',
        'lock_stock',
        'safety_stock',
        'exchange_limit',
        'status',
        'sort',
        'category_id',
        'product_type',
        'product_id',
    ];


    protected function casts() : array
    {
        return [
            'point'          => 'integer',
            'price'          => MoneyCast::class,
            'stock'          => 'integer',
            'lock_stock'     => 'integer',
            'safety_stock'   => 'integer',
            'exchange_limit' => 'integer',
            'sort'           => 'integer',
            'category_id'    => 'integer',
            'payment_mode'   => PointsProductPaymentModeEnum::class,
            'status'         => PointsProductStatusEnum::class,
        ];
    }


    protected static function boot() : void
    {
        parent::boot();

        // 生命周期钩子
        static::saving(function ($model) {
            // 保存时的业务逻辑
            $model->updateStockStatus();
        });

        static::deleting(function ($model) {
            // 删除时的业务逻辑
        });

        static::restoring(function ($model) {
            // 恢复时的业务逻辑
        });
    }

    /**
     * 获取库存信息值对象
     */
    public function getStockInfo() : StockInfo
    {
        return new StockInfo(
            $this->stock,
            $this->lock_stock ?? 0,
            $this->safety_stock
        );
    }

    /**
     * 设置库存信息
     */
    public function setStockInfo(StockInfo $stockInfo) : void
    {
        $this->stock        = $stockInfo->totalStock;
        $this->lock_stock   = $stockInfo->lockStock;
        $this->safety_stock = $stockInfo->safetyStock;
    }

    /**
     * 获取支付信息值对象
     */
    public function getPaymentInfo() : PaymentInfo
    {
        return new PaymentInfo(
            $this->payment_mode,
            $this->point,
            $this->price_amount
        );
    }

    /**
     * 设置支付信息
     */
    public function setPaymentInfo(PaymentInfo $paymentInfo) : void
    {
        $this->payment_mode = $paymentInfo->paymentMode;
        $this->point        = $paymentInfo->pointAmount;
        $this->price_amount = $paymentInfo->moneyAmount;
    }

    /**
     * 获取兑换限制值对象
     */
    public function getExchangeLimit() : ExchangeLimit
    {
        return new ExchangeLimit($this->exchange_limit);
    }

    /**
     * 设置兑换限制
     */
    public function setExchangeLimit(ExchangeLimit $exchangeLimit) : void
    {
        $this->exchange_limit = $exchangeLimit->maxPerUser;
    }

    /**
     * 获取可用库存
     */
    public function getAvailableStock() : int
    {
        return $this->getStockInfo()->getAvailableStock();
    }

    /**
     * 获取实际现金价格
     */
    public function getActualMoneyPrice() : float
    {
        return $this->price_amount;
    }

    /**
     * 获取总价值（积分转换为现金）
     */
    public function getTotalValue(float $pointsRate = 0.01) : float
    {
        $pointsMoney = $this->point * $pointsRate;
        return $pointsMoney + $this->price_amount;
    }

    /**
     * 检查是否为混合支付模式
     */
    public function isMixedPaymentMode() : bool
    {
        return $this->payment_mode === PointsProductPaymentModeEnum::MIXED;
    }

    /**
     * 检查是否为纯积分支付模式
     */
    public function isPointsOnlyPaymentMode() : bool
    {
        return $this->payment_mode === PointsProductPaymentModeEnum::POINTS;
    }


    /**
     * 检查是否可以兑换指定数量
     */
    public function canExchange(int $quantity) : bool
    {
        // 检查商品状态
        if ($this->status !== PointsProductStatusEnum::ON_SALE) {
            return false;
        }

        // 检查库存
        if ($this->getAvailableStock() < $quantity) {
            return false;
        }

        // 检查兑换限制
        $exchangeLimit = $this->getExchangeLimit();
        if (!$exchangeLimit->checkOrderLimit($quantity)) {
            return false;
        }

        return true;
    }

    /**
     * 减少库存
     */
    public function decreaseStock(int $quantity) : bool
    {
        $stockInfo = $this->getStockInfo();

        if (!$stockInfo->decreaseStock($quantity)) {
            return false;
        }

        $this->setStockInfo($stockInfo);
        $this->updateStockStatus();

        return true;
    }

    /**
     * 锁定库存
     */
    public function lockStock(int $quantity) : bool
    {
        $stockInfo = $this->getStockInfo();

        if (!$stockInfo->lockStock($quantity)) {
            return false;
        }

        $this->setStockInfo($stockInfo);
        return true;
    }

    /**
     * 解锁库存
     */
    public function unlockStock(int $quantity) : bool
    {
        $stockInfo = $this->getStockInfo();

        if (!$stockInfo->unlockStock($quantity)) {
            return false;
        }

        $this->setStockInfo($stockInfo);
        return true;
    }

    /**
     * 增加库存
     */
    public function increaseStock(int $quantity) : void
    {
        $stockInfo = $this->getStockInfo();
        $stockInfo->increaseStock($quantity);
        $this->setStockInfo($stockInfo);
        $this->updateStockStatus();
    }

    /**
     * 更新库存状态
     */
    public function updateStockStatus() : void
    {
        $stockInfo = $this->getStockInfo();

        if ($stockInfo->isSoldOut()) {
            $this->status = PointsProductStatusEnum::SOLD_OUT;
        } elseif ($this->status === PointsProductStatusEnum::SOLD_OUT && $stockInfo->getAvailableStock() > 0) {
            $this->status = PointsProductStatusEnum::ON_SALE;
        }
    }

    /**
     * 上架商品
     */
    public function putOnSale() : void
    {
        if ($this->getAvailableStock() > 0) {
            $this->status = PointsProductStatusEnum::ON_SALE;
        }
    }

    /**
     * 下架商品
     */
    public function putOffSale() : void
    {
        $this->status = PointsProductStatusEnum::OFF_SALE;
    }

    /**
     * 检查是否上架
     */
    public function isOnSale() : bool
    {
        return $this->status === PointsProductStatusEnum::ON_SALE;
    }

    /**
     * 检查是否售罄
     */
    public function isSoldOut() : bool
    {
        return $this->status === PointsProductStatusEnum::SOLD_OUT;
    }

    /**
     * 检查是否下架
     */
    public function isOffSale() : bool
    {
        return $this->status === PointsProductStatusEnum::OFF_SALE;
    }

    /**
     * 关联分类
     */
    public function category()
    {
        return $this->belongsTo(PointsProductCategory::class, 'category_id');
    }

    /**
     * 关联兑换订单
     */
    public function exchangeOrders()
    {
        return $this->hasMany(PointsExchangeOrder::class, 'point_product_id');
    }
} 