<?php

namespace RedJasmine\Support\Helpers\Inputs;

use Throwable;

class InputHelper
{


    /**
     * 转换为LIST
     * @param mixed $input
     * @return array
     */
    public static function toList(mixed $input)
    {
        return array_values(self::toArray($input));
    }

    /**
     * 转换为数组
     * @param mixed $input
     * @return array
     */
    public static function toArray(mixed $input) : array
    {
        if (is_array($input)) {
            return (array)$input;
        }
        if (is_string($input)) {
            try {
                return json_decode($input, true, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable $throwable) {
                return [];
            }
        }
        return [];
    }

}
