<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;

/**
 * @property string $owner_type
 * @property int $owner_id
 */
trait HasOwner
{

    protected function getOwnerColumn() : string
    {
        return property_exists($this, 'ownerColumn') ? $this->ownerColumn : 'owner';
    }

    public function owner() : Attribute
    {
        return Attribute::make(
            get: fn() => UserData::from([
                'type' => $this->{$this->getOwnerKey('type')},
                'id'   => $this->{$this->getOwnerKey('id')},
            ]),
            set: fn(?UserInterface $creator = null) => [
                $this->getOwnerKey('type') => $creator?->getType(),
                $this->getOwnerKey('id')   => $creator?->getID(),
            ],
        );
    }

    protected function getOwnerKey(string $key) : string
    {
        return $this->getOwnerColumn().'_'.$key;
    }


    public function scopeOnlyOwner(Builder $query, ?UserInterface $owner = null) : Builder
    {
        if ($owner) {
            return $query->where($this->getOwnerColumn().'_type', $owner->getType())
                         ->where($this->getOwnerColumn().'_id', $owner->getID());
        }
        return $query;
    }

}
