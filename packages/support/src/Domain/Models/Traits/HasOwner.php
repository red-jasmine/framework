<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;

/**
 * @property string $owner_type
 * @property int $owner_id
 * @property string $ownerColumn
 * @property bool $withOwnerNickname
 * @property bool $withOwnerAvatar
 */
trait HasOwner
{

    protected function getOwnerColumn() : string
    {
        return property_exists($this, 'ownerColumn') ? $this->ownerColumn : 'owner';
    }

    protected function withOwnerNickname() : bool
    {
        return property_exists($this, 'withOwnerNickname') ? $this->withOwnerNickname : false;
    }

    protected function withOwnerAvatar() : bool
    {
        return property_exists($this, 'withOwnerAvatar') ? $this->withOwnerAvatar : false;
    }

    public function owner() : Attribute
    {
        return Attribute::make(
            get: fn() => UserData::from([
                'type'     => $this->{$this->getOwnerKey('type')},
                'id'       => $this->{$this->getOwnerKey('id')},
                'nickname' => $this->withOwnerNickname() ? ($this->{$this->getOwnerKey('nickname')} ?? null) : null,
                'avatar'   => $this->withOwnerAvatar() ? ($this->{$this->getOwnerKey('avatar')} ?? null) : null,

            ]),
            set: fn(?UserInterface $user = null) => array_merge([
                $this->getOwnerKey('type') => $user?->getType(),
                $this->getOwnerKey('id')   => $user?->getID(),
            ], $this->withOwnerNickname() ? [
                $this->getOwnerKey('nickname') => $user?->getNickname(),
            ] : [], $this->withOwnerAvatar() ? [
                $this->getOwnerKey('avatar') => $user?->getAvatar(),
            ] : []),
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
