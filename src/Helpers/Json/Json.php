<?php

namespace RedJasmine\Support\Helpers\Json;

use JsonException;

class Json
{

    /**
     * @param null $value
     *
     * @return array|null
     */
    public static function toArray($value = null) : array|null
    {
        if (blank($value)) {
            return null;
        }
        if (is_string($value)) {
            try {
                return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $throwable) {
                return null;
            }
        }
        if (is_array($value)) {
            return (array)$value;
        }

        return (array)$value;

    }

}
