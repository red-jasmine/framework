<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class PointProductCategoryData extends Data
{
    public UserInterface $owner;
    public string $name;
    public ?string $slug = null;
    public ?string $description = null;
    public ?string $image = null;
    public int $sort = 0;
    public string $status = 'enable';
} 