<?php

namespace RedJasmine\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 所属人 查询
 */
trait OwnerScope
{
    public function scopeOwner(Builder $builder, UserInterface $owner) : void
    {
        $builder->where('owner_type', $owner->getUserType())
                ->where('owner_uid', $owner->getUID());
    }

}
