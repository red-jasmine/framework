<?php

namespace RedJasmine\Product\Domain\Tag\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class ProductTag extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
}
