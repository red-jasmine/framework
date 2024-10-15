<?php

namespace RedJasmine\Payment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Contracts\UserInterface;

class PaymentChannelApp extends Model
{

    protected $casts = [
        'modes' => 'array'
    ];


    public function scopeOwner(Builder $builder, UserInterface $owner) : void
    {
        $builder->where('owner_type', $owner->getUserType())->where('owner_id', $owner->getID());
    }
}
