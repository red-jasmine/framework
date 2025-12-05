<?php

namespace RedJasmine\Order\Domain\Types;

use RedJasmine\Support\Foundation\Manager\ServiceManager;

class OrderTypeManage extends ServiceManager
{

    /**
     * @var array<string,OrderTypeInterface>
     */
    protected const  PROVIDERS = [
        'standard' => OrderStandardType::class,
    ];


    public function labels() : array
    {
        $labels = array_map(function ($provider) {
            return $provider::label();
        }, static::PROVIDERS);

        static::$customCreators;

        return $labels;
    }
}