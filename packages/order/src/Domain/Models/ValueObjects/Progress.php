<?php

namespace RedJasmine\Order\Domain\Models\ValueObjects;

use RedJasmine\Support\Foundation\Data\Data;

class Progress extends Data
{

    public ?int $progress = null;

    public ?int $progressTotal = null;


    /**
     * 是否可以小于历史值
     * @var bool
     */
    public bool $isAllowLess = false;

}
