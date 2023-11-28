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

    public function withCreator(?UserInterface $creator) : void
    {
        if ($creator) {
            $this->creator_type = $creator->getUserType();
            $this->creator_uid  = $creator->getUID();
        }

    }


    public function withUpdater(?UserInterface $updater) : void
    {
        if ($updater) {
            $this->updater_type = $updater->getUserType();
            $this->updater_uid  = $updater->getUID();
        }
    }


}
