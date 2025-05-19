<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Facades\ServiceContext;

/**
 * @property  string $creator_type
 * @property  string $creator_id
 * @property  string $updater_type
 * @property  string $updater_id
 * @property  bool $withOperatorNickname
 * @property  bool $withOperatorAvatar
 */
trait HasOperator
{


    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasOperator() : void
    {

        static::creating(callback: function ($model) {
            $model->creator = ServiceContext::getOperator();
        });
        static::updating(callback: function ($model) {
            $model->updater = ServiceContext::getOperator();
        });
    }


    protected function withOperatorNickname() : bool
    {
        return property_exists($this, 'withOperatorNickname') ? $this->withOperatorNickname : false;
    }

    protected function withOperatorAvatar() : bool
    {
        return property_exists($this, 'withOperatorAvatar') ? $this->withOperatorAvatar : false;
    }

    public function scopeOnlyCreator(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('creator_type', $owner->getType())
                     ->where('creator_id', $owner->getID());

    }


    public function scopeOnlyUpdater(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('updater_type', $owner->getType())
                     ->where('updater_id', $owner->getID());

    }


    public function creator() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->creator_type && $this->creator_id) ? UserData::from([
                'type'     => $this->creator_type,
                'id'       => $this->creator_id,
                'nickname' => $this->withOperatorNickname() ? ($this->creator_nickname ?? null) : null,
                'avatar'   => $this->withOperatorAvatar() ? ($this->creator_avatar ?? null) : null,
            ]) : null,
            set: fn(?UserInterface $user = null) => array_merge([
                'creator_type' => $user?->getType(),
                'creator_id'   => $user?->getID(),
            ], $this->withOperatorNickname() ? [
                'creator_nickname' => $user?->getNickname(),
            ] : [], $this->withOperatorAvatar() ? [
                'creator_avatar' => $user?->getAvatar(),
            ] : []),
        );
    }

    public function updater() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->updater_type && $this->updater_id) ? UserData::from([
                'type'     => $this->updater_type,
                'id'       => $this->updater_id,
                'nickname' => $this->withOperatorNickname() ? ($this->updater_nickname ?? null) : null,
                'avatar'   => $this->withOperatorAvatar() ? ($this->updater_avatar ?? null) : null,
            ]) : null,
            set: fn(?UserInterface $user = null) => array_merge([
                'updater_type' => $user?->getType(),
                'updater_id'   => $user?->getID(),
            ], $this->withOperatorNickname() ? [
                'updater_nickname' => $user?->getNickname(),
            ] : [], $this->withOperatorAvatar() ? [
                'updater_avatar' => $user?->getAvatar(),
            ] : []),
        );
    }


}
