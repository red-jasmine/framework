<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use RedJasmine\Announcement\Domain\Data\CategoryData;
use RedJasmine\Support\Contracts\UserInterface;

class CategoryHideCommand extends CategoryData
{
    public UserInterface $operator;
}
