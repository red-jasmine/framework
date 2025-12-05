<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class PromoterSetParentCommand extends Data
{
    public int $id;
    
    public int $parentId;
    
    public ?string $remarks = null;
} 