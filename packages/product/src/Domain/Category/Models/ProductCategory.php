<?php

namespace RedJasmine\Product\Domain\Category\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;

class ProductCategory extends BaseCategoryModel
{
    use HasCategoryTranslations;

    protected string $translationModel = ProductCategoryTranslation::class;

    /**
     * @return bool
     */
    public function isAllowUse() : bool
    {
        if ($this->is_leaf === false) {
            return false;
        }

        if ($this->status !== UniversalStatusEnum::ENABLE) {
            return false;
        }
        // TODO 所有上级是否支持使用
        return true;
    }

}
