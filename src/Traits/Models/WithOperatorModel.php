<?php

namespace RedJasmine\Support\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;

trait WithOperatorModel
{


    public function scopeCreator(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('creator_type', $owner->getUserType())
                     ->where('creator_uid', $owner->getUID());

    }

    public function scopeUpdater(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('updater_type', $owner->getUserType())
                     ->where('updater_uid', $owner->getUID());

    }

    public function withCreator(?UserInterface $user) : void
    {
        if (!$user) {
            return;
        }
        $this->creator_type     = $user->getUserType();
        $this->creator_uid      = $user->getUID();
        $this->creator_nickname = $user->getNickname();
    }


    public function withUpdater(?UserInterface $user) : void
    {
        if (!$user) {
            return;
        }
        $this->updater_type     = $user->getUserType();
        $this->updater_uid      = $user->getUID();
        $this->updater_nickname = $user->getNickname();
    }


}
