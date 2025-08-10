<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use RedJasmine\Announcement\Domain\Data\CategoryData;
use RedJasmine\Support\Contracts\UserInterface;

class CategoryMoveCommand extends CategoryData
{
    public UserInterface $operator;
    public ?int $parentId = null;
    public int $sort = 0;
}
