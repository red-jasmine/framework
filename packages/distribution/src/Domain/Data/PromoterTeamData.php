<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

class PromoterTeamData extends BaseCategoryData
{

    /**
     * 团长ID
     */
    public ?int $leaderId = null;
}
