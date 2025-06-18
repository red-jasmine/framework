<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterGroupRepository extends EloquentRepository implements PromoterGroupRepositoryInterface
{
    protected static string $eloquentModelClass = PromoterGroup::class;
}