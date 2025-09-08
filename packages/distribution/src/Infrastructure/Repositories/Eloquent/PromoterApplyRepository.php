<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterApplyRepository extends Repository implements PromoterApplyRepositoryInterface
{
    protected static string $modelClass = PromoterApply::class;
}