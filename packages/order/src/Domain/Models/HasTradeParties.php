<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Support\Contracts\UserInterface;

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

    public function seller() : MorphTo
    {
        return $this->morphTo('seller', 'seller_type', 'seller_id');
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

    public function buyer() : MorphTo
    {
        return $this->morphTo('buyer', 'buyer_type', 'buyer_id');
    }

    public function scopeOnlyBuyer(Builder $query, UserInterface $buyer) : Builder
    {
        return $query->where('buyer_type', $buyer->getType())->where('buyer_id', $buyer->getID());

    }


}
