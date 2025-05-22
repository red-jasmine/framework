<?php

namespace RedJasmine\Product\Application\Group\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class ProductGroupCreateCommand extends BaseCategoryData
{


    public UserInterface $owner;


}
