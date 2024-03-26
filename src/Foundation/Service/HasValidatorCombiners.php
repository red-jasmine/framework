<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Foundation\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorCombinerInterface;

trait HasValidatorCombiners
{

    protected ?Validator $validator = null;

    public function makeValidator(array $data, array $rules = [], array $messages = [], array $attributes = []) : ?Validator
    {
        if ($this->mergeValidatorCombiners()) {
            return $this->validator = $this->validator ?? $this->combinerValidator(\Illuminate\Support\Facades\Validator::make($data, $rules, $messages, $attributes));
        }
        $this->validator = null;
        return $this->validator;
    }

    public function getValidator() : ?Validator
    {
        return $this->validator;
    }


    public function setValidator(?Validator $validator) : static
    {
        $this->validator = $validator;
        return $this;
    }


    /**
     *
     * 验证组合器
     * @var array|string[]|ValidatorCombinerInterface[]
     */
    protected static array $globalValidatorCombiners = [];

    /**
     * @param $validatorCombiner
     *
     * @return void
     */
    public static function extendValidatorCombiner($validatorCombiner) : void
    {
        static::$globalValidatorCombiners[] = $validatorCombiner;
    }

    public static function getGlobalValidatorCombiners() : array
    {
        return self::$globalValidatorCombiners;
    }

    public static function setGlobalValidatorCombiners(array $globalValidatorCombiners) : void
    {
        self::$globalValidatorCombiners = $globalValidatorCombiners;
    }


    protected ?array $validatorCombiners = null;

    public function getValidatorCombiners() : array
    {
        return $this->validatorCombiners??$this->service::getValidatorCombiners();
    }


    public function setValidatorCombiners(array $validatorCombiners) : static
    {
        $this->validatorCombiners = $validatorCombiners;
        return $this;
    }

    protected function addValidatorCombiner($validatorCombiner) : static
    {
        $this->validatorCombiners[] = $validatorCombiner;
        return $this;
    }

    /**
     *
     * @return ValidatorCombinerInterface[]|array
     */
    public function validatorCombiners() : array
    {
        return [];
    }


    protected function mergeValidatorCombiners() : array
    {
        return array_merge(static::$globalValidatorCombiners, $this->validatorCombiners(), $this->getValidatorCombiners());
    }


    /**
     * 组合验证器
     *
     * @param Validator $validator
     *
     * @return Validator
     */
    protected function combinerValidator(Validator $validator) : Validator
    {
        foreach ($this->mergeValidatorCombiners() as $validatorCombiner) {
            $validatorCombiner = app($validatorCombiner);
            if ($validatorCombiner instanceof ActionAwareValidatorCombiner) {
                $validatorCombiner->setAction($this);
            }
            if ($validatorCombiner instanceof ValidatorAwareValidatorCombiner) {
                $validatorCombiner->setValidator($validator);
            }
            $validator->addRules($validatorCombiner->rules());
            $validator->setCustomMessages($validatorCombiner->messages());
            $validator->addCustomAttributes($validatorCombiner->attributes());
        }
        return $validator;
    }
}
