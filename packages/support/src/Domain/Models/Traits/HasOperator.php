<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Facades\ServiceContext;

/**
 * @property  string $creator_type
 * @property  int    $creator_id
 * @property  string $updater_type
 * @property  int    $updater_id
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

        static::creating(callback:function ($model) {
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


    public function creator() : Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                if (blank($attributes['creator_type'] ?? null)) {
                    return null;
                }
                return UserData::from([ 'type' => $attributes['creator_type'], 'id' => $attributes['creator_id'], ]);
            },
            set: static fn(?UserInterface $user) => [
                'creator_type' => $user?->getType(),
                'creator_id'   => $user?->getID()
            ]

        );
    }


    public function updater() : Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                if (blank($attributes['updater_type'] ?? null)) {
                    return null;
                }
                return UserData::from([ 'type' => $attributes['updater_type'], 'id' => $attributes['updater_id'], ]);
            }, set: static fn(?UserInterface $user) => [
            'updater_type' => $user?->getType(),
            'updater_id'   => $user?->getID()
        ]

        );
    }

}
