<?php

namespace RedJasmine\Support\Helpers\Enums;


trait EnumsHelper
{

    public function name() : string
    {
        return self::label()[$this->value] ?? $this->value;
    }

    public function label() : string
    {
        return self::labels()[$this->value] ?? $this->name;
    }

    public function color()
    {
        return self::colors()[$this->value] ?? $this->value;
    }

    public static function names() : array
    {
        return self::labels();
    }
    public static function options() : array
    {
        return self::labels();
    }

}
