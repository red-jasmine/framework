<?php

namespace RedJasmine\Support\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;

trait WithOwnerModel
{

    public function scopeOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('owner_type', $owner->getUserType())
                     ->where('owner_uid', $owner->getUID());

    }

    public function withOwner(?UserInterface $owner) : void
    {
        if ($owner) {
            $this->owner_type = $owner->getUserType();
            $this->owner_uid  = $owner->getUID();
        }


    }

}
