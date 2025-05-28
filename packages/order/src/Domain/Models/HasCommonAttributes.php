<?php

namespace RedJasmine\Order\Domain\Models;

use RedJasmine\Support\Domain\Casts\UserInterfaceCast;

trait HasCommonAttributes
{


    protected function getCommonAttributesCast() : array
    {
        return [
            'guide'   => UserInterfaceCast::class.':1',
            'store'   => UserInterfaceCast::class.':1',
            'channel' => UserInterfaceCast::class.':1',
            'source'  => UserInterfaceCast::class,

        ];
    }
}