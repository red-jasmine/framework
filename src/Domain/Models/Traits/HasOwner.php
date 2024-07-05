<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;

/**
 * @property string $owner_type
 * @property int    $owner_id
 */
trait HasOwner
{


    protected string $ownerColumn = 'owner';

    public function owner() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return UserData::from([ 'type' => $attributes[$this->ownerColumn . '_type'], 'id' => $attributes[$this->ownerColumn . '_type'], ]);
            },
            set: fn(?UserInterface $user) => [
                $this->ownerColumn . '_type' => $user?->getType(),
                $this->ownerColumn . '_id'   => $user?->getID()
            ]

        );
    }

    public function scopeOnlyOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where($this->ownerColumn . '_type', $owner->getType())
                     ->where($this->ownerColumn . '_id', $owner->getID());
    }

}
