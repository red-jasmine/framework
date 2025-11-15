<?php

namespace RedJasmine\Product\Domain\Product\Models;

use RedJasmine\Money\Data\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Product\Domain\Brand\Models\ProductBrand;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Extensions\ProductExtension;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Money\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


/**
 * @property $variants
 * @property $has_variants
 * @property Money $price
 * @property ?Money $market_price
 * @property ?Money $cost_price
 * @property ?Carbon $available_at
 * @property ?Carbon $paused_at
 * @property ?Carbon $unavailable_at
 * @property ?Carbon $modified_at
 */
class Product extends Model implements OperatorInterface, OwnerInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    public static string $defaultAttrsName     = '';
    public static string $defaultAttrsSequence = '';
    public               $incrementing         = false;
    protected            $appends              = ['price', 'market_price', 'cost_price'];

    protected static function boot() : void
    {
        parent::boot();

        static::saving(callback: static function (Product $product) {

            if ($product->relationLoaded('extendProductGroups')) {

                if ($product->extendProductGroups?->count() > 0) {
                    if (!is_array($product->extendProductGroups->first())) {
                        $product->extendProductGroups()->sync($product->extendProductGroups);
                    } else {
                        $product->extendProductGroups()->sync($product->extendProductGroups->pluck('id')->toArray());
                    }
                    $product->load('extendProductGroups');
                } else {
                    $product->extendProductGroups()->sync([]);
                }
            }

            if ($product->relationLoaded('tags')) {

                if ($product->tags?->count() > 0) {
                    if (!is_array($product->tags->first())) {
                        $product->tags()->sync($product->tags);
                    } else {
                        $product->tags()->sync($product->tags->pluck('id')->toArray());
                    }

                } else {
                    $product->tags()->sync([]);
                }
                $product->load('tags');
            }


            if ($product->relationLoaded('services')) {

                if ($product->services?->count() > 0) {
                    if (!is_array($product->services->first())) {
                        $product->services()->sync($product->services);
                    } else {
                        $product->services()->sync($product->services->pluck('id')->toArray());
                    }

                } else {
                    $product->services()->sync([]);
                }
                $product->load('services');
            }

        });
        static::deleting(callback: static function (Product $product) {
            $product->extension()->delete();
            $product->variants()->delete();

        });

        static::restoring(callback: static function (Product $product) {
            $product->variants()->withTrashed()->whereNot('status', ProductStatusEnum::DELETED)->restore();
            $product->extension()->withTrashed()->restore();
        });

        static::forceDeleting(callback: static function (Product $product) {
            $product->variants()->forceDelete();
            $product->extension()->forceDelete();
            $product->extendProductGroups()->detach();
            $product->tags()->detach();
        });
    }

    public function extendProductGroups() : BelongsToMany
    {


        return $this->belongsToMany(ProductGroup::class,
            (new ProductExtendGroupPivot())->getTable(),
            'product_id',
            'product_group_id')
                    ->using(ProductExtendGroupPivot::class)
                    ->withTimestamps();

    }

    public function tags() : BelongsToMany
    {

        return $this->belongsToMany(ProductTag::class,
            'product_tag_pivot',
            'product_id',
            'product_tag_id')
                    ->using(ProductTagPivot::class)
                    ->withTimestamps();

    }

    public function services() : BelongsToMany
    {

        return $this->belongsToMany(ProductService::class,
            (new ProductServicePivot)->getTable(),
            'product_id',
            'product_service_id')
                    ->using(ProductServicePivot::class)
                    ->withTimestamps();

    }

    /**
     *  附加信息
     * @return HasOne
     */
    public function extension() : HasOne
    {

        return $this->hasOne(ProductExtension::class, 'id', 'id');
    }

    /**
     * 所有规格
     * @return HasMany
     */
    public function variants() : HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    /**
     * 价格列表
     * @return HasMany
     */
    /**
     * 商品级别价格汇总
     */
    public function prices() : HasMany
    {
        return $this->hasMany(\RedJasmine\Product\Domain\Price\Models\ProductPrice::class, 'product_id', 'id');
    }

    /**
     * 变体价格（通过变体关联）
     */
    public function variantPrices() : HasMany
    {
        return $this->hasMany(\RedJasmine\Product\Domain\Price\Models\ProductVariantPrice::class, 'product_id', 'id');
    }

    public function casts() : array
    {
        return [
            'product_type'              => ProductTypeEnum::class,  // 商品类型
            'shipping_types'          => 'array',// 配送方式  可选，多个
            'status'                    => ProductStatusEnum::class,// 状态
            'sub_stock'                 => SubStockTypeEnum::class,// 扣库存方式
            'freight_payer'             => FreightPayerEnum::class,// 运费承担方
            'has_variants'              => 'boolean',
            'is_brand_new'              => 'boolean',
            'taxable'                   => 'boolean',
            'available_at'              => 'datetime',
            'paused_at'                 => 'datetime',
            'unavailable_at'            => 'datetime',
            'modified_at'               => 'datetime',
            'start_sale_time'           => 'datetime',
            'end_sale_time'             => 'datetime',
            'is_hot'                    => 'boolean',
            'is_new'                    => 'boolean',
            'is_best'                   => 'boolean',
            'is_benefit'                => 'boolean',
            'is_customized'             => 'boolean',
            'is_alone_order'            => 'boolean',
            'is_pre_sale'               => 'boolean',
            'currency'                  => CurrencyCast::class,
            'price'                     => MoneyCast::class.':currency,price,1',
            'market_price'              => MoneyCast::class.':currency,market_price,1',
            'cost_price'                => MoneyCast::class.':currency,cost_price,1',
            'order_quantity_limit_type' => OrderQuantityLimitTypeEnum::class,
        ];
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('extension', ProductExtension::make());
            $instance->setRelation('variants', Collection::make());
            $instance->setRelation('extendProductGroups', Collection::make());
            $instance->setRelation('tags', Collection::make());
        }

        return $instance;
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
        return $this->belongsTo(ProductBrand::class, 'brand_id', 'id');
    }

    public function productGroup() : BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id', 'id');
    }

    public function addVariant(ProductVariant $variant) : static
    {
        $variant->market     = $this->market;
        $variant->owner      = $this->owner;
        $variant->product_id = $this->id;
        if (!$this->variants->where('id', $variant->id)->first()) {
            $this->variants->push($variant);
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


    public function scopeDraft(Builder $query)
    {
        return $query->where('status', ProductStatusEnum::DRAFT);
    }

    /**
     * 销售中
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->whereIn('status', [
            ProductStatusEnum::AVAILABLE,
        ]);
    }


    // 仓库中的
    public function scopeWarehoused(Builder $query)
    {
        return $query->whereIn('status', [
            ProductStatusEnum::PAUSED,
            ProductStatusEnum::UNAVAILABLE,
            ProductStatusEnum::DRAFT,
            ProductStatusEnum::FORBIDDEN,
            ProductStatusEnum::ARCHIVED,
        ]);
    }


    public function scopeStockAlarming(Builder $query)
    {
        return $query->whereRaw(DB::raw('stock <= safety_stock'));
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
            case ProductStatusEnum::AVAILABLE:
                $this->available_at = now();
                // 清空其他状态时间
                $this->paused_at      = null;
                $this->unavailable_at = null;
                break;
            case ProductStatusEnum::PAUSED:
                $this->paused_at = now();
                break;
            case ProductStatusEnum::UNAVAILABLE:
                $this->unavailable_at = now();
                break;
            case ProductStatusEnum::FORBIDDEN:
            case ProductStatusEnum::ARCHIVED:
            case ProductStatusEnum::DELETED:
                // 这些状态记录到 unavailable_at
                $this->unavailable_at = now();
                break;
        }
    }

    /**
     * 是否允许销售
     * @return boolean
     * @throws ProductException
     */
    public function isAllowSale() : bool
    {
        if ($this->status !== ProductStatusEnum::AVAILABLE) {

            return false;
            throw  ProductException::newFromCodes(ProductException::PRODUCT_FORBID_SALE);
        }

        return true;

    }

    /**
     * @param  int  $quantity
     *
     * @return bool
     * @throws ProductException
     */
    public function isAllowNumberBuy(int $quantity) : bool
    {
        if ($this->min_limit > 0 && $this->min_limit > $quantity) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_MIN_LIMIT);

        }

        if ($this->max_limit > 0 && $this->max_limit < $quantity) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_MAX_LIMIT);
        }

        if ($this->step_limit > 1 && ($quantity % $this->step_limit) !== 0) {
            throw  ProductException::newFromCodes(ProductException::PRODUCT_STEP_LIMIT);
        }

        return true;

    }

    public function freightTemplate() : BelongsTo
    {
        return $this->belongsTo(LogisticsFreightTemplate::class, 'freight_template_id', 'id');
    }


    public function getSkuBySkuId(int $skuId) : ?ProductVariant
    {
        return $this->variants->where('id', $skuId)->firstOrFail();
    }


    /**
     * 获取可选的发货类型
     * @return array
     */
    public function getAllowShippingTypes() : array
    {
        $types = $this->product_type->getAllowShippingTypes();
        if (!$this->product_type->isAllowDeliveryMethods()) {
            return $types;
        }
        $allowShippingTypes = [];
        foreach ($this->shipping_types as $deliveryMethod) {
            if (in_array(ShippingTypeEnum::from($deliveryMethod), $types)) {
                $allowShippingTypes[] = ShippingTypeEnum::from($deliveryMethod);
            }
        }
        return $allowShippingTypes;
    }


    // 获取默认的变体
    public function getDefaultVariant() : ProductVariant
    {
        $defaultVariant = null;
        // 判断当前是否为未创建的
        if ($this->exists) {
            // 查询数据的值
            $defaultVariant = $this->variants->where('attrs_sequence', $this::$defaultAttrsSequence)->first();
        }

        if (!$defaultVariant) {
            $defaultVariant = new ProductVariant();
        }
        $defaultVariant->product_id     = $this->id;
        $defaultVariant->attrs_sequence = static::$defaultAttrsSequence;
        $defaultVariant->attrs_name     = static::$defaultAttrsName;
        return $defaultVariant;
    }
}
