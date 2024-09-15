<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;

class Property extends Data
{

    /**
     * 属性ID
     * @var int
     */
    public int $pid;


    /**
     * @var Collection<PropValue>|null
     */
    public ?Collection $values = null;


}
