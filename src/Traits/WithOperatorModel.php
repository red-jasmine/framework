<?php

namespace RedJasmine\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;

trait WithOperatorModel
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
