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
            get: fn() => UserData::from([
                'type'     => $this->creator_type,
                'id'       => $this->creator_id,
                'nickname' => $this->withOperatorNickname() ? ($this->creator_nickname ?? null) : null
            ]),
            set: fn(?UserInterface $creator = null) => array_merge([
                'creator_type' => $creator?->getType(),
                'creator_id'   => $creator?->getID(),
            ], $this->withOperatorNickname() ? [
                'creator_nickname' => $creator?->getNickname(),
            ] : []),
        );
    }

    public function updater() : Attribute
    {
        return Attribute::make(
            get: fn() => UserData::from([
                'type'     => $this->updater_type,
                'id'       => $this->updater_id,
                'nickname' => $this->withOperatorNickname() ? ($this->updater_nickname ?? null) : null
            ]),
            set: fn(?UserInterface $creator = null) => array_merge([
                'updater_type' => $creator?->getType(),
                'updater_id'   => $creator?->getID(),
            ], $this->withOperatorNickname() ? [
                'updater_nickname' => $creator?->getNickname(),
            ] : []),
        );
    }


}
