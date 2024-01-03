<?php

namespace RedJasmine\Support\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Helpers\UserObjectBuilder;

/**
 * @property string $owner_type
 * @property int    $owner_id
 */
trait WithOwnerModel
{


    public function getOwner() : UserInterface
    {
        return new UserObjectBuilder([ 'type' => $this->owner_type, 'id' => $this->owner_id ]);
    }

    public function scopeOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('owner_type', $owner->getType())
                     ->where('owner_id', $owner->getID());

    }

    public function withOwner(?UserInterface $owner) : void
    {
        if ($owner) {
            $this->owner_type = $owner->getType();
            $this->owner_id   = $owner->getID();
        }
    }

}
