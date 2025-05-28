<?php

namespace RedJasmine\Order\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

/**
 * 门店
 */
class Store extends Data
{

    public string $type;

    public string $id;

    public string $name;

    /**
     * 自定义编码
     * @var string|null
     */
    public ?string $sn;

}