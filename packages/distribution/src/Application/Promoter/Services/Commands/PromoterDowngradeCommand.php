<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class PromoterDowngradeCommand extends Data
{
    public int $id;
    
    public int $level;
    
    public ?string $remarks = null;
} 