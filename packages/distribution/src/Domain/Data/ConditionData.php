<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Data\Data;

class ConditionData extends Data
{

    /**
     * 条件类型
     * @var string
     */
    public string $type;


    /**
     * 条件值
     * @var string
     */
    public string $value;

}