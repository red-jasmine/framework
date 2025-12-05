<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;


/**
 * 品牌模型
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $slogan
 */
class ProductBrand extends BaseCategoryModel
{
    use HasCategoryTranslations;

    protected string $translationModel = ProductBrandTranslation::class;


    public function isAllowUse() : bool
    {
        return $this->status === UniversalStatusEnum::ENABLE;
    }

}
