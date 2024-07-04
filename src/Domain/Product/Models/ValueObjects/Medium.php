<?php

namespace RedJasmine\Product\Domain\Product\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class Medium extends ValueObject
{

    public null|int|string $id;

    public string $url;

    /**
     * 位置
     * @var int
     */
    public int $position = 0;


}
