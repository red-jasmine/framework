<?php

namespace RedJasmine\Article\Domain\Data;


use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class ArticleCategoryData extends BaseCategoryData
{
    public UserInterface $owner;
}