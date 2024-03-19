<?php

namespace RedJasmine\Support\Foundation;

use Illuminate\Support\Facades\Validator;

abstract class ValidatorManage
{


    protected \Illuminate\Validation\Validator $validator;

    public function __construct(array $data = [])
    {
        $this->validator = Validator::make($data, $this->rules(), $this->messages(), $this->attributes());
    }

    public function validator() : \Illuminate\Validation\Validator
    {
        return $this->validator;
    }

    /**
     * 验证规则
     * @return array
     */
    abstract public function rules() : array;


    public function messages() : array
    {
        return [];
    }

    public function attributes() : array
    {
        return [];
    }


}
