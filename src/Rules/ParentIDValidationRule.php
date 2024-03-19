<?php

namespace RedJasmine\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Validation\Rules\DatabaseRule;
use Illuminate\Validation\Validator;

class ParentIDValidationRule implements ValidationRule, ValidatorAwareRule, DataAwareRule
{
    use Conditionable, DatabaseRule;

    protected array     $data;
    protected Validator $validator;
    protected array     $parameters = [];

    protected   $id;

    public function __construct($id)
    {
        $this->id = $id;

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

        if ((string)$this->id === (string)$value) {
            $fail(':attribute 不能为当前ID');
            return;
        }
        // TODO $value 不能是 当前ID 的所有子集 ID
    }


}
