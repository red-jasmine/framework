<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterGroupRepository extends Repository implements PromoterGroupRepositoryInterface
{
    protected static string $modelClass = PromoterGroup::class;
}