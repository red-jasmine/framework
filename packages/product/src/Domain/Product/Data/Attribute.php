<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;

class Attribute extends Data
{

    /**
     * 属性ID
     * @var int
     */
    public int $pid;


    public ?string $name;

    public ?string $unit;

    /**
     * @var Collection<PropValue>|null
     */
    public ?Collection $values = null;


}

