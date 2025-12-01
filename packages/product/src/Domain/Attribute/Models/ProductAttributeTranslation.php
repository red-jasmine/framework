<?php

namespace RedJasmine\Product\Domain\Attribute\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品属性翻译模型
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property string|null $unit
 * @property TranslationStatusEnum $translation_status
 * @property Carbon|null $translated_at
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ProductAttributeTranslation extends Model implements OperatorInterface
{
    use SoftDeletes;
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOperator;

    public $incrementing = false;

    protected $table = 'product_attribute_translations';

    protected $fillable = [
        'attribute_id',
        'locale',
        'name',
        'description',
        'unit',
        'translation_status',
        'translated_at',
        'reviewed_at',
    ];

    protected $casts = [
        'translation_status' => TranslationStatusEnum::class,
        'translated_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * 关联到商品属性
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id', 'id');
    }
}

