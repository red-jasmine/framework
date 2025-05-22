<?php

namespace RedJasmine\Product\Domain\Tag\Data;

use RedJasmine\Product\Domain\Tag\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class ProductTag extends BaseCategoryData
{

    public UserInterface $owner;



}
