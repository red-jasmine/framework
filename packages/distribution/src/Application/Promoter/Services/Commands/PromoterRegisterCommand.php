<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Data\PromoterData;

/**
 * 招募申请
 */
class PromoterRegisterCommand extends PromoterApplyData
{
    public PromoterData $promoter;


}