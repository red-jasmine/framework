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


    public function owner() : Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                return UserData::from([ 'type' => $attributes['owner_type'], 'id' => $attributes['owner_id'], ]);
            },
            set: static fn(?UserInterface $user) => [
                'owner_type' => $user?->getType(),
                'owner_id'   => $user?->getID()
            ]

        );
    }

    public function scopeOnlyOwner(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('owner_type', $owner->getType())->where('owner_id', $owner->getID());
    }

}
