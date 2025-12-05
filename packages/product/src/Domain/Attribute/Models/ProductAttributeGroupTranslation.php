<?php

namespace RedJasmine\Product\Domain\Attribute\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryTranslationModel;

/**
 * 商品属性组翻译模型
 */
class ProductAttributeGroupTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{
    /**
     * 关联到商品属性组
     */
    public function productAttributeGroup(): BelongsTo
    {
        return $this->belongsTo(ProductAttributeGroup::class, 'locale_id');
    }
}

