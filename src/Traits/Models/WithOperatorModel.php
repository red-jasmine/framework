<?php

namespace RedJasmine\Support\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * @property  string $creator_type
 * @property  int    $creator_id
 * @property  string $updater_type
 * @property  int    $updater_id
 */
trait WithOperatorModel
{


    public function scopeCreator(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('creator_type', $owner->getType())
                     ->where('creator_id', $owner->getID());

    }

    public function scopeUpdater(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('updater_type', $owner->getType())
                     ->where('updater_id', $owner->getID());

    }

    public function withCreator(?UserInterface $creator) : void
    {
        if ($creator) {
            $this->creator_type = $creator->getType();
            $this->creator_id  = $creator->getID();
        }

    }


    public function withUpdater(?UserInterface $updater) : void
    {
        if ($updater) {
            $this->updater_type = $updater->getType();
            $this->updater_id  = $updater->getID();
        }
    }


}
