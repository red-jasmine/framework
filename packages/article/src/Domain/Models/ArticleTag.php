<?php

namespace RedJasmine\Article\Domain\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class ArticleTag extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
}
