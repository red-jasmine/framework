<?php

namespace RedJasmine\Product\Domain\Category\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\BaseCategoryTranslationModel;
use RedJasmine\Support\Domain\Models\OperatorInterface;

/**
 * 商品类目翻译模型
 *
 * @property int $id
 * @property int $locale_id
 * @property string $locale
 * @property string $name
 * @property string|null $slogan
 * @property string|null $description
 * @property \RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum $translation_status
 * @property \Carbon\Carbon|null $translated_at
 * @property \Carbon\Carbon|null $reviewed_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class ProductCategoryTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{
    /**
     * 关联到商品类目
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'locale_id');
    }
}

