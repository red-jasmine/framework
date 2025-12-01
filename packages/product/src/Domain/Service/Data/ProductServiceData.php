<?php

namespace RedJasmine\Product\Domain\Service\Data;

use RedJasmine\Support\Domain\Data\BaseCategoryData;

class ProductServiceData extends BaseCategoryData
{

    /**
     * 翻译数据
     *
     * @var ProductServiceTranslation[]
     */
    public ?array $translations = null;


}
