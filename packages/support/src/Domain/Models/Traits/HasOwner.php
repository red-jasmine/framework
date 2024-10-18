<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * @property string $owner_type
 * @property int $owner_id
 */
trait HasOwner
{


    protected string $ownerColumn = 'owner';

    public function owner() : MorphTo
    {
        return $this->morphTo($this->ownerColumn, $this->ownerColumn . '_type', $this->ownerColumn . '_id');
    }


    public function setOwnerAttribute(UserInterface $owner) : static
    {
        $this->setAttribute($this->ownerColumn . '_type', $owner->getType());
        $this->setAttribute($this->ownerColumn . '_id', $owner->getID());
        return $this;
    }


    public function scopeOnlyOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where($this->ownerColumn . '_type', $owner->getType())
                     ->where($this->ownerColumn . '_id', $owner->getID());
    }

}
