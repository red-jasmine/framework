<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class PromoterApplyCommand extends Data
{
    public UserInterface $owner;
    
    public string $name;
    
    public ?string $remarks = null;
    
    public ?int $parentId = null;
} 