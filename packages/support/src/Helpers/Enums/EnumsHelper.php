<?php

namespace RedJasmine\Support\Helpers\Enums;


trait EnumsHelper
{


    public static function names() : array
    {
        return self::labels();
    }

    public static function options() : array
    {
        return self::labels();
    }

    public static function lists() : array
    {
        $lists = [];
        foreach (self::options() as $value => $label) {
            $lists[] = [
                'label' => $label,
                'value' => $value,
                'icon'  => null,
                'color' => self::colors()[$value]
            ];
        }
        return $lists;
    }

    public static function comments(string $title = '') : string
    {
        $enums = array_map(function ($key, $value) {
            return $key.'('.$value.')';
        }, array_keys(static::labels()), static::labels());
        return $title.': '.implode(',', $enums);

    }

    public function name() : string
    {
        return self::label()[$this->value] ?? $this->value;
    }

    public function label() : string
    {
        return self::labels()[$this->value] ?? $this->name;
    }

    public function getLabel() : ?string
    {
        return $this->label();
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getColor() : string|array|null
    {
        return $this->color();
    }

    public function color() : string
    {
        return self::colors()[$this->value] ?? $this->value;
    }

    public static function colors() : array
    {
        // 根据所有枚举值按顺序分配颜色 返回一个枚举值对于 颜色的数组
        $baseColors = static::baseColors();
        $count      = count(self::values());
        $colors     = [];
        foreach (self::values() as $index => $value) {
            $colors[$value] = $baseColors[($index % count($baseColors))];
        }

        return $colors;

    }

    // 定义一个基础颜色数组函数

    public static function baseColors() : array
    {
        return [
            'success',
            'danger',
            'primary',
            'warning',
            'info',
            'gray',
        ];
    }

    public static function values() : array
    {
        return array_map(fn($case) => $case->value, static::cases());
    }

    public function getIcon() : ?string
    {
        return static::icons()[$this->value] ?? null;
    }

    public static function icons() : array
    {
        return [];
    }
}
