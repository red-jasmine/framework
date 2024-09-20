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

    public function color() : string
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

    public static function values() : array
    {
        return array_map(fn($case) => $case->value, static::cases());
    }

    public static function comments(string $title = '') : string
    {
        $enums = array_map(function ($key, $value) {
            return $key . '(' . $value.')';
        }, array_keys(static::labels()), static::labels());
        return $title . ': ' . implode(',', $enums);

    }


}
