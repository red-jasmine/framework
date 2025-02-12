<?php

namespace RedJasmine\ResourceUsage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RedJasmine\ResourceUsage\ResourceUsage
 */
class ResourceUsage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RedJasmine\ResourceUsage\ResourceUsage::class;
    }
}
