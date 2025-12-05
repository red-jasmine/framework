<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class ConditionData extends Data
{


    public bool $enabled = true;

    /**
     * 条件类型
     * @var string
     */
    public string $name;


    /**
     * 条件值
     * @var string
     */
    public string $value;

}