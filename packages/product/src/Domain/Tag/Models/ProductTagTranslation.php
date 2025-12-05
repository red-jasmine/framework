<?php

namespace RedJasmine\Product\Domain\Tag\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryTranslationModel;

/**
 * 商品标签翻译模型
 */
class ProductTagTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{
    /**
     * 关联到商品标签
     */
    public function productTag(): BelongsTo
    {
        return $this->belongsTo(ProductTag::class, 'locale_id');
    }
}

