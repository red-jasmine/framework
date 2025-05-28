<?php

namespace RedJasmine\Order\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

class Guide extends Data
{

    public string $type;

    public string $id;

    public string $nickname;

    /**
     * 自定义编码
     * @var string|null
     */
    public ?string $sn;
}