<?php

namespace RedJasmine\Support\Helpers\Enums;


trait EnumsHelper
{

    public function name() : string
    {
        return self::names()[$this->value] ?? $this->value;
    }

    public function color()
    {
        return self::colors()[$this->value] ?? $this->value;
    }


    public static function options() : array
    {
        $names = self::names();
        return  $names;
        return array_map(function ($key, $value) {
            return [
                'value' => $key,
                'label' => $value
            ];
        }, array_keys($names), $names);

    }

}
