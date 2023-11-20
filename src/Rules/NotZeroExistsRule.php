<?php

namespace RedJasmine\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;


class NotZeroExistsRule implements ValidationRule, ValidatorAwareRule, DataAwareRule
{
    protected array     $data;
    protected Validator $validator;
    protected array     $parameters = [];

    public function __construct()
    {
        $this->parameters = func_get_args();

    }

    public function setData(array $data) : void
    {
        $this->data = $data;
    }

    public function setValidator(Validator $validator) : void
    {
        $this->validator = $validator;
    }


    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (($value !== 0) && !$this->validator->validateExists($attribute, $value, $this->parameters)) {
            $fail(':attribute 无效');
        }

    }


}
