<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Support\Data\Data;

class PromoterAuditCommand extends Data
{
    public int $id;
    
    public bool $approved;
    
    public ?string $remarks = null;
} 