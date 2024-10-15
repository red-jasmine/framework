<?php

namespace RedJasmine\Product\Domain\Property\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PropertyNameRule implements ValidationRule
{
    /**
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {

        // 不能包含特殊字符
        if (Str::contains($value, [ ':', ';', ',' ])) {
            $fail('名称不支持特殊符号');
        }
    }
}
