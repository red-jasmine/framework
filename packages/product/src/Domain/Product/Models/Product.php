<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\HasSupplier;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Product\Domain\Brand\Models\Brand;
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
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class Product extends Model implements OperatorInterface, OwnerInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    use HasSupplier;

    public $incrementing = false;


    public static string $defaultPropertiesName = '';


    public static string $defaultPropertiesSequence = '';

    public function casts() : array
    {
        return [
            'product_type'              => ProductTypeEnum::class,  // 商品类型
            'shipping_type'             => ShippingTypeEnum::class,// 发货类型
            'status'                    => ProductStatusEnum::class,// 状态
            'sub_stock'                 => SubStockTypeEnum::class,// 扣库存方式
            'freight_payer'             => FreightPayerEnum::class,// 运费承担方
            'is_multiple_spec'          => 'boolean',
            'is_brand_new'              => 'boolean',
            'stop_sale_time'            => 'datetime',
            'on_sale_time'              => 'datetime',
            'sold_out_time'             => 'datetime',
            'modified_time'             => 'datetime',
            'start_sale_time'           => 'datetime',
            'end_sale_time'             => 'datetime',
            'is_hot'                    => 'boolean',
            'is_new'                    => 'boolean',
            'is_best'                   => 'boolean',
            'is_benefit'                => 'boolean',
            'is_customized'             => 'boolean',
            'is_alone_order'            => 'boolean',
            'is_pre_sale'               => 'boolean',
            'is_from_supplier'          => 'boolean',
            'price'                     => MoneyCast::class,
            'market_price'              => MoneyCast::class,
            'cost_price'                => MoneyCast::class,
            'order_quantity_limit_type' => OrderQuantityLimitTypeEnum::class,
        ];
    }


    protected $appends = ['price', 'market_price', 'cost_price'];

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
            $product->skus()->delete();

        });

        static::restoring(callback: static function (Product $product) {
            $product->skus()->withTrashed()->whereNot('status', ProductStatusEnum::DELETED)->restore();
            $product->extension()->withTrashed()->restore();
        });

        static::forceDeleting(callback: static function (Product $product) {
            $product->skus()->forceDelete();
            $product->extension()->forceDelete();
            $product->extendProductGroups()->detach();
            $product->tags()->detach();
        });
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists); // TODO: Change the autogenerated stub

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('extension', ProductExtension::make());
            $instance->setRelation('skus', Collection::make());
            $instance->setRelation('extendProductGroups', Collection::make());
            $instance->setRelation('tags', Collection::make());
        }

        return $instance;
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
            (new ProductTagPivot)->getTable(),
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
    public function skus() : HasMany
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
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

    public function productGroup() : BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id', 'id');
    }

    public function addSku(ProductSku $sku) : static
    {
        $sku->market     = $this->market;
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
     * 销售中
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeOnSale(Builder $query)
    {
        return $query->whereIn('status', [
            ProductStatusEnum::ON_SALE,
        ]);
    }

    // 售罄的
    public function scopeSoldOut(Builder $query)
    {
        return $query->where('status', ProductStatusEnum::SOLD_OUT);
    }

    // 仓库中的
    public function scopeWarehoused(Builder $query)
    {
        return $query->whereIn('status', [
            ProductStatusEnum::STOP_SALE,
            ProductStatusEnum::FORBID_SALE,
            ProductStatusEnum::DRAFT,
        ]);
    }

    public function scopeStockAlarming(Builder $query)
    {
        return $query->whereRaw(DB::raw('stock <= safety_stock'));
    }

    public function scopeDraft(Builder $query)
    {
        return $query->where('status', ProductStatusEnum::DRAFT);
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
            case ProductStatusEnum::FORBID_SALE:
            case ProductStatusEnum::STOP_SALE:
                $this->stop_sale_time = now();
                break;
            case ProductStatusEnum::DELETED:
                $this->stop_sale_time = now();
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
        ], true)) {


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

}
