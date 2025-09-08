<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterTeamRepository extends Repository implements PromoterTeamRepositoryInterface
{
    protected static string $modelClass = PromoterTeam::class;
}