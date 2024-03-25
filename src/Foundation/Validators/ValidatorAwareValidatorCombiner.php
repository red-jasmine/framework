<?php

namespace RedJasmine\Support\Foundation\Validators;

use Illuminate\Validation\Validator;

interface ValidatorAwareValidatorCombiner
{

    public function setValidator(Validator $validator) : void;


}
