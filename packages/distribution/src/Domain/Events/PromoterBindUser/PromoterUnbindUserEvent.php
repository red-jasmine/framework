<?php

namespace RedJasmine\Distribution\Domain\Events\PromoterBindUser;

use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PromoterUnbindUserEvent 
{
    use Dispatchable;
    use SerializesModels;
    public function __construct(public PromoterBindUser $promoterBindUser)
    {
      
    }
} 