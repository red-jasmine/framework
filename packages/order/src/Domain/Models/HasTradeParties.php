<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;

trait HasTradeParties
{


    public function scopeOnlySeller(Builder $query, UserInterface $seller) : Builder
    {
        return $query->where('seller_type', $seller->getType())->where('seller_id', $seller->getID());
    }

    public function setSellerAttribute(UserInterface $user) : static
    {
        $this->setAttribute('seller_type', $user->getType());
        $this->setAttribute('seller_id', $user->getID());
        if ($this->withTradePartiesNickname) {
            $this->setAttribute('seller_nickname', $user->getNickname());
        }
        return $this;
    }


    public function setBuyerAttribute(UserInterface $user) : static
    {

        $this->setAttribute('buyer_type', $user->getType());
        $this->setAttribute('buyer_id', $user->getID());
        if ($this->withTradePartiesNickname) {
            $this->setAttribute('buyer_nickname', $user->getNickname());
        }

        return $this;
    }

    public function seller() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return UserData::from([
                                          'type'     => $attributes['seller_type'],
                                          'id'       => $attributes['seller_id'],
                                          'nickname' => $attributes['seller_nickname'] ?? null
                                      ]);
            },
            set: function (?UserInterface $user) {
                $attributes = [
                    'seller_type' => $user?->getType(),
                    'seller_id'   => $user?->getID(),
                ];
                if ($this->withTradePartiesNickname) {
                    $attributes['seller_nickname'] = $user?->getNickname();
                }

                return $attributes;
            }

        );
    }


    public function buyer() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return UserData::from([
                                          'type'     => $attributes['buyer_type'],
                                          'id'       => $attributes['buyer_id'],
                                          'nickname' => $attributes['buyer_nickname'] ?? null
                                      ]);
            },
            set: function (?UserInterface $user) {
                $attributes = [
                    'buyer_type' => $user?->getType(),
                    'buyer_id'   => $user?->getID(),
                ];
                if ($this->withTradePartiesNickname) {
                    $attributes['buyer_nickname'] = $user?->getNickname();
                }
                return $attributes;
            }
        );
    }


    public function scopeOnlyBuyer(Builder $query, UserInterface $buyer) : Builder
    {
        return $query->where('buyer_type', $buyer->getType())->where('buyer_id', $buyer->getID());

    }


}
