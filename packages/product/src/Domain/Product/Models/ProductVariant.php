<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use RedJasmine\Money\Data\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Product\Domain\Media\Models\ProductMedia;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Money\Casts\MoneyCast;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property ProductStatusEnum $status
 * @property Money $price
 * @property ?Money $market_price
 * @property ?Money $cost_price
 * @property  $attrs_name
 * @property  $attrs_sequence
 * @property  $version
 * @property ?Carbon $deleted_at
 */
class ProductVariant extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasOwner;

    public    $incrementing = false;
    protected $appends      = ['price', 'market_price', 'cost_price'];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * 变体价格列表
     * @return HasMany
     */
    public function prices() : HasMany
    {
        return $this->hasMany(ProductVariantPrice::class, 'variant_id', 'id');
    }

    public function setDeleted() : void
    {
        $this->deleted_at = $this->deleted_at ?? now();
        $this->status     = ProductStatusEnum::DELETED;
    }

    public function setAvailable() : void
    {
        $this->deleted_at = null;
        $this->status     = ProductStatusEnum::AVAILABLE;
    }

    public function setProduct(Product $product)
    {
        $this->status     = ProductStatusEnum::AVAILABLE;
        $this->deleted_at = null;

    }

    public function stocks() : HasMany
    {
        return $this->hasMany(ProductStock::class, 'variant_id', 'id');
    }

    /**
     * 变体媒体资源
     * @return HasMany
     */
    public function media() : HasMany
    {
        return $this->hasMany(ProductMedia::class, 'variant_id', 'id');
    }

    public function addMedia(ProductMedia $media) : void
    {
        $media->owner      = $this->owner;
        $media->product_id = $this->product_id;
        $media->variant_id = $this->id;


        $this->media->push($media);
    }

    protected function casts() : array
    {
        return [
            'status'       => ProductStatusEnum::class,// 状态
            'modified_at'  => 'datetime',
            'currency'     => CurrencyCast::class,
            'price'        => MoneyCast::class.':currency,price,1',
            'market_price' => MoneyCast::class.':currency,market_price,1',
            'cost_price'   => MoneyCast::class.':currency,cost_price,1',
        ];
    }

}
