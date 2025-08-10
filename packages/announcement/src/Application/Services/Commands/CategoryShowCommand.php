<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use RedJasmine\Announcement\Domain\Data\CategoryData;
use RedJasmine\Support\Contracts\UserInterface;

class CategoryShowCommand extends CategoryData
{
    public UserInterface $operator;
}
