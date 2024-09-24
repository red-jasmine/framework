<?php

namespace RedJasmine\Support\Helpers\Enums;


use phpDocumentor\Reflection\Types\Static_;

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
            return $key.'('.$value.')';
        }, array_keys(static::labels()), static::labels());
        return $title.': '.implode(',', $enums);

    }

    // 定义一个基础颜色数组函数

    public static function baseColors() : array
    {
        return [
            'primary',
            'success',
            'danger',
            'warning',
            'info',
            'dark',
        ];
    }

    public static function colors() : array
    {
        // 根据所有枚举值按顺序分配颜色 返回一个枚举值对于 颜色的数组
        $baseColors = static::baseColors();
        $count      = count(self::values());
        $colors     = [];
        foreach (self::values() as $index => $value) {
            $colors[$value] = $baseColors[$index % $count];
        }

        return $colors;

    }


}
