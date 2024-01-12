<?php

namespace RedJasmine\Support\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\DataTransferObjects\UserDTO;

/**
 * @property string                                      $owner_type
 * @property int                                         $owner_id
 */
trait HasOwner
{


    public function owner() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return UserDTO::from([ 'type' => $attributes['owner_type'], 'id' => $attributes['owner_id'], ]);
            },
            set: fn(?UserInterface $user) => [
                'owner_type' => $user?->getType(),
                'owner_id'   => $user?->getID()
            ]

        );
    }

    public function scopeOnlyOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('owner_type', $owner->getType())
                     ->where('owner_id', $owner->getID());

    }

}
