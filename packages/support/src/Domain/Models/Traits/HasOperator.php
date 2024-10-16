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
 * @property  int $creator_id
 * @property  string $updater_type
 * @property  int $updater_id
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


    public function scopeOnlyCreator(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('creator_type', $owner->getType())->where('creator_id', $owner->getID());

    }


    public function scopeOnlyUpdater(Builder $query, UserInterface $owner) : Builder
    {
        return $query->where('updater_type', $owner->getType())->where('updater_id', $owner->getID());

    }


    public function setCreatorAttribute(UserInterface $owner) : static
    {
        $this->setAttribute('creator_type', $owner->getType());
        $this->setAttribute('creator_id', $owner->getID());

        return $this;
    }

    public function setUpdaterAttribute(UserInterface $owner) : static
    {
        $this->setAttribute('updater_type', $owner->getType());
        $this->setAttribute('updater_id', $owner->getID());
        return $this;
    }

    public function creator() : MorphTo
    {
        return $this->morphTo(__FUNCTION__, __FUNCTION__ . '_type', __FUNCTION__ . '_id');
    }

    public function updater() : MorphTo
    {
        return $this->morphTo(__FUNCTION__, __FUNCTION__ . '_type', __FUNCTION__ . '_id');
    }


}
