<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

/**
 * 品牌翻译模型
 *
 * @property int $id
 * @property int $product_brand_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property string|null $slogan
 * @property TranslationStatusEnum $translation_status
 * @property Carbon|null $translated_at
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ProductBrandTranslation extends Model implements OperatorInterface
{
    use SoftDeletes;
    use HasOperator;

    protected $table = 'product_brand_translations';

    protected $fillable = [
        'product_brand_id',
        'locale',
        'name',
        'description',
        'slogan',
        'translation_status',
        'translated_at',
        'reviewed_at',
    ];

    /**
     * 关联到品牌
     */
    public function brand() : BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    protected function casts() : array
    {
        return [
            'translation_status' => TranslationStatusEnum::class,
            'translated_at'      => 'datetime',
            'reviewed_at'        => 'datetime',
        ];
    }
}

