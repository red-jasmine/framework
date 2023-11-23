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

    public function withOwner(?UserInterface $user) : void
    {
        if (!$user) {
            return;
        }
        $this->owner_type     = $user->getUserType();
        $this->owner_uid      = $user->getUID();
        $this->owner_nickname = $user->getNickname();
    }

}
