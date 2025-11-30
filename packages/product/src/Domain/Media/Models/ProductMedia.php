<?php

namespace RedJasmine\Product\Domain\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductVariant;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品媒体资源模型
 *
 * @property int $id
 * @property string $owner_type
 * @property string $owner_id
 * @property int|null $product_id
 * @property int|null $variant_id
 * @property MediaTypeEnum $media_type
 * @property string|null $mime_type
 * @property string $path 文件路径（相对路径）
 * @property string $url 媒体URL（访问器，自动拼接CDN地址）
 * @property string|null $file_name
 * @property int|null $file_size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $alt_text
 * @property int $position
 * @property bool $is_primary
 * @property bool $is_enabled
 * @property array|null $extra
 */
class ProductMedia extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'product_media';

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
        }
        return $instance;
    }

    /**
     * 关联商品
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * 关联变体
     */
    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    /**
     * 作用域：商品级别的媒体
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId)
                     ->whereNull('variant_id');
    }

    /**
     * 作用域：变体级别的媒体
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * 作用域：按所有者筛选
     */
    public function scopeForOwner($query, $owner)
    {
        return $query->where('owner_type', get_class($owner))
                     ->where('owner_id', $owner->id);
    }

    /**
     * 作用域：独立媒体（未关联商品）
     */
    public function scopeStandalone($query)
    {
        return $query->whereNull('product_id');
    }

    /**
     * 作用域：主图
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * 作用域：按类型筛选
     */
    public function scopeOfType($query, MediaTypeEnum $mediaType)
    {
        return $query->where('media_type', $mediaType);
    }

    /**
     * 作用域：已启用
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * 作用域：按位置排序
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc')
                     ->orderBy('created_at', 'asc');
    }

    /**
     * 获取媒体URL（自动拼接CDN地址）
     *
     * 使用 Storage facade 自动拼接CDN地址，CDN配置在 config/filesystems.php 中设置
     * 如果路径已经是完整URL，则直接返回
     *
     * @return string
     */
    public function getUrlAttribute() : string
    {
        // 如果路径已经是完整URL，直接返回
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return $this->path;
        }

        // 使用Storage获取URL（会自动拼接CDN或存储配置的URL）
        // 默认使用 public 磁盘，如需使用其他磁盘，可在 extra 字段中配置
        $disk = $this->extra['disk'] ?? 'public';
        $path = ltrim($this->path, '/');

        // 获取磁盘配置的基础URL
        $diskConfig = config("filesystems.disks.{$disk}");
        $baseUrl    = $diskConfig['url'] ?? config('app.url');

        // 拼接完整URL
        return rtrim($baseUrl, '/').'/'.$path;
    }

    protected function casts() : array
    {
        return [
            'media_type' => MediaTypeEnum::class,
            'is_primary' => 'boolean',
            'is_enabled' => 'boolean',
            'extra'      => 'array',
        ];
    }
}

