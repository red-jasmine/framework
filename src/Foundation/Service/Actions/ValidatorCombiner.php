<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Foundation\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorCombinerInterface;

class ValidatorCombiner implements ValidatorCombinerInterface, ActionAwareValidatorCombiner, ValidatorAwareValidatorCombiner
{

    protected Validator $validator;

    protected ResourceAction $action;

    public function setAction(ResourceAction $action) : void
    {
        $this->action = $action;
    }

    public function setValidator(Validator $validator) : void
    {
        $this->validator = $validator;
    }


    public function rules() : array
    {
        return [];
    }


    public function messages() : array
    {
        return [];
    }

    public function attributes() : array
    {
        return [];
    }


}
