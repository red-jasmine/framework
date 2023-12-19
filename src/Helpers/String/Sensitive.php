<?php

namespace RedJasmine\Support\Helpers\String;

use Illuminate\Support\Str;

class Sensitive
{


    public string $mask = '*';

    public function string(string $string = null, $showLastCharacters = 2) : ?string
    {
        if (blank($string)) {
            return null;
        }
        return str_repeat($this->mask, mb_strlen($string, 'UTF-8') - $showLastCharacters) .
               mb_substr($string, -$showLastCharacters, null, 'UTF-8');

    }

}
