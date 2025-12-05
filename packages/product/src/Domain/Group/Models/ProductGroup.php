<?php

namespace RedJasmine\Product\Domain\Group\Models;

use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

/**
 * 商品分组模型
 */
class ProductGroup extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
    use HasCategoryTranslations;

    protected string $translationModel = ProductGroupTranslation::class;
}

