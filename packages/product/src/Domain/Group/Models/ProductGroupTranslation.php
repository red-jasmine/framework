<?php

namespace RedJasmine\Product\Domain\Group\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryTranslationModel;

/**
 * 商品分组翻译模型
 */
class ProductGroupTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{
    /**
     * 关联到商品分组
     */
    public function productGroup(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'locale_id');
    }
}

