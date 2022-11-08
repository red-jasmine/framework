<?php

namespace RedJasmine\Support\Helpers\Enums;


trait EnumsHelper
{

    public function name() : string
    {
        return self::names()[$this->value]??$this->value;
    }

    public function color()
    {
        return self::colors()[$this->value]??$this->value;
    }

}
