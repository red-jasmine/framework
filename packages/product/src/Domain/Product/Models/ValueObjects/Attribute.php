<?php

namespace RedJasmine\Product\Domain\Product\Models\ValueObjects;

use Illuminate\Support\Collection;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class Attribute extends ValueObject
{

    /**
     * 属性ID
     * @var int
     */
    public int $aid;

    /**
     * 名称
     * @var string
     */
    public string $name;

    /**
     * 单位
     * @var string|null
     */
    public ?string $unit;
    /**
     * 属性值
     * @var Collection<PropValue>
     */
    public Collection $values;


}

