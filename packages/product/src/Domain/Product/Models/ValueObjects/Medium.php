<?php

namespace RedJasmine\Product\Domain\Product\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

/**
 * 媒体
 */
class Medium extends Data
{

    /**
     * 资源ID
     * @var int|string|null
     */
    public null|int|string $id;

    /**
     * 资源地址
     * @var string
     */
    public string $url;

    /**
     * 位置
     * @var int
     */
    public int $position = 0;


}
