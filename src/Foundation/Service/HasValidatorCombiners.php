<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Validation\Validator;
use RedJasmine\Support\Foundation\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Support\Foundation\Validators\ValidatorCombinerInterface;

trait HasValidatorCombiners
{


    protected function initializeHasValidatorCombiners() : void
    {
        $this->validatorCombiners = array_merge($this->validatorCombiners, $this->validatorCombiners(), static::$globalValidatorCombiners);
    }


    protected ?Validator $validator = null;

    public function makeValidator(array $data, array $rules = [], array $messages = [], array $attributes = []) : ?Validator
    {
        if (count($this->validatorCombiners) > 0) {
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


    protected array $validatorCombiners = [];

    public function getValidatorCombiners() : array
    {
        return $this->validatorCombiners;
    }


    public function setValidatorCombiners(array $validatorCombiners) : static
    {
        $this->validatorCombiners = $validatorCombiners;
        return $this;
    }

    public function addValidatorCombiner($validatorCombiner) : static
    {
        if (!is_array($validatorCombiner)) {
            $validatorCombiner = [ $validatorCombiner ];
        }
        array_push($this->validatorCombiners, ...$validatorCombiner);

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

    /**
     * 组合验证器
     *
     * @param Validator $validator
     *
     * @return Validator
     */
    protected function combinerValidator(Validator $validator) : Validator
    {
        foreach ($this->validatorCombiners as $validatorCombiner) {
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
