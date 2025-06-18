<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterTeamRepository extends EloquentRepository implements PromoterTeamRepositoryInterface
{
    protected static string $eloquentModelClass = PromoterTeam::class;
}