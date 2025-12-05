<?php

namespace RedJasmine\Article\Domain\Models;

use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

class ArticleTag extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
}
