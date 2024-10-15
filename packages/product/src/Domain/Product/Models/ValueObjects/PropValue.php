<?php

namespace RedJasmine\Product\Domain\Product\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class PropValue extends ValueObject
{

    /**
     * 属性ID
     * @var int
     */
    public int $vid;

    public string $name;

    public ?string $alias = null;


}
