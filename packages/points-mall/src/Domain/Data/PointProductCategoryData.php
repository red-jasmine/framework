<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

class PointProductCategoryData extends BaseCategoryData
{
    public UserInterface $owner;

} 