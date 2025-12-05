<?php

namespace RedJasmine\Product\Domain\Tag\Models;

use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

/**
 * 商品标签模型
 */
class ProductTag extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
    use HasCategoryTranslations;

    protected string $translationModel = ProductTagTranslation::class;
}
