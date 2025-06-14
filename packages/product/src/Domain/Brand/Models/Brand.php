<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;


class Brand extends BaseCategoryModel
{

    public function isAllowUse() : bool
    {
        return $this->status === UniversalStatusEnum::ENABLE;
    }

}
