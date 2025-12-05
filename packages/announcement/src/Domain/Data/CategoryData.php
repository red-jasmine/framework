<?php

namespace RedJasmine\Announcement\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

class CategoryData extends BaseCategoryData
{
    public UserInterface $owner;
    public string $biz;
}
