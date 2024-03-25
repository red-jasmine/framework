<?php

namespace RedJasmine\Support\Foundation\Validators;

interface ValidatorCombinerInterface
{

    public function rules() : array;

    public function messages() : array;

    public function attributes() : array;
}
