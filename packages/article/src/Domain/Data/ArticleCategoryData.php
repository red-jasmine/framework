<?php

namespace RedJasmine\Article\Domain\Data;


use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

class ArticleCategoryData extends BaseCategoryData
{
    public UserInterface $owner;
}