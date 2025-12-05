<?php

namespace RedJasmine\Product\Domain\Attribute\Models;

use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

class ProductAttributeGroup extends BaseCategoryModel
{
    use HasCategoryTranslations;

    protected string $translationModel = ProductAttributeGroupTranslation::class;
}
