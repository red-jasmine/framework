<?php

namespace RedJasmine\Product\Domain\Service\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasCategoryTranslations;

class ProductService extends BaseCategoryModel implements OperatorInterface
{

    use HasCategoryTranslations;

    protected string $translationModel = ProductServiceTranslation::class;

}
