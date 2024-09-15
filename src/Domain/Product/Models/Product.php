<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServicesCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\HasSupplier;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;


class Product extends Model implements OperatorInterface, OwnerInterface
{
    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    use HasSupplier;

    public $incrementing = false;


    public function casts() : array
    {
        return [
            'product_type'     => ProductTypeEnum::class,  // 商品类型
            'shipping_type'    => ShippingTypeEnum::class,// 发货类型
            'status'           => ProductStatusEnum::class,// 状态
            'sub_stock'        => SubStockTypeEnum::class,// 扣库存方式
            'freight_payer'    => FreightPayerEnum::class,// 运费承担方
            'is_multiple_spec' => 'boolean',
            'off_sale_time'    => 'datetime',
            'on_sale_time'     => 'datetime',
            'sold_out_time'    => 'datetime',
            'modified_time'    => 'datetime',
            'is_hot'           => 'boolean',
            'is_new'           => 'boolean',
            'is_best'          => 'boolean',
            'is_benefit'       => 'boolean',
            'promise_services' => PromiseServicesCastTransformer::class,
            'price'            => AmountCastTransformer::class,
            'market_price'     => AmountCastTransformer::class,
            'cost_price'       => AmountCastTransformer::class,
        ];
    }


    /**
     * 类目
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }


    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }


    public function sellerCategory() : BelongsTo
    {
        return $this->belongsTo(ProductSellerCategory::class, 'seller_category_id', 'id');
    }

    /**
     *  附加信息
     * @return HasOne
     */
    public function info() : HasOne
    {
        return $this->hasOne(ProductInfo::class, 'id', 'id');
    }


    /**
     * 所有规格
     * @return HasMany
     */
    public function skus() : HasMany
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }


    public function addSku(ProductSku $sku) : static
    {
        $sku->owner      = $this->owner;
        $sku->product_id = $this->id;
        if (!$this->skus->where('id', $sku->id)->first()) {
            $this->skus->push($sku);
        }
        return $this;

    }

    /**
     * 系列
     * @return HasOneThrough
     */
    public function series() : HasOneThrough
    {
        return $this->hasOneThrough(
            ProductSeries::class,
            ProductSeriesProduct::class,
            'product_id',
            'id',
            'id',
            'series_id'
        )->with(['products']);
    }


    /**
     * 设置状态
     *
     * @param  ProductStatusEnum  $status
     *
     * @return void
     */
    public function setStatus(ProductStatusEnum $status) : void
    {

        $this->status = $status;

        if (!$this->isDirty('status')) {
            return;
        }
        switch ($status) {
            case ProductStatusEnum::ON_SALE:
                $this->on_sale_time = now();
                break;
            case ProductStatusEnum::SOLD_OUT:
                $this->sold_out_time = now();
                break;
            case ProductStatusEnum::OFF_SHELF:
                $this->off_sale_time = now();
                break;
            case ProductStatusEnum::PRE_SALE:
                break;
            case ProductStatusEnum::FORBID_SALE:
                $this->off_sale_time = now();
                break;
            case ProductStatusEnum::DELETED:
                $this->off_sale_time = now();
        }
    }


    /**
     * 是否允许销售
     * @return boolean
     * @throws ProductException
     */
    public function isAllowSale() : bool
    {
        if (!in_array($this->status, [
            ProductStatusEnum::ON_SALE,
            ProductStatusEnum::PRE_SALE
        ], true)) {


            throw  ProductException::newFromCodes(ProductException::PRODUCT_FORBID_SALE);
        }

        return true;

    }


    /**
     * @param  int  $num
     *
     * @return bool
     * @throws ProductException
     */
    public function isAllowNumberBuy(int $num) : bool
    {
        if ($this->min_limit > 0 && $this->min_limit > $num) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_MIN_LIMIT);

        }

        if ($this->max_limit > 0 && $this->max_limit < $num) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_MAX_LIMIT);
        }

        if ($this->step_limit > 1 && ($num % $this->step_limit) !== 0) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_STEP_LIMIT);
        }

        return true;

    }

}
