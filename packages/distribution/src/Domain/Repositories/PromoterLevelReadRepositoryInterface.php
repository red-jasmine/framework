<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PromoterLevelReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * @param  int  $level
     *
     * @return PromoterLevel
     */
    public function findLevel(int $level) : PromoterLevel;
}