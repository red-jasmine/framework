<?php

namespace RedJasmine\Order\Domain\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;

class OrderPolicy
{
    use HandlesAuthorization;

    use HasDefaultPolicy;
    public static function getModel() : string
    {
        return Order::class;
    }

}
