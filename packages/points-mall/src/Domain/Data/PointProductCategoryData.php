<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class PointProductCategoryData extends BaseCategoryData
{
    public UserInterface $owner;

} 