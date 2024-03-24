<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Contracts\Validation\Validator;
use RedJasmine\Product\Services\Product\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorCombinerInterface;

trait HasValidatorCombiners
{
    /**
     *
     * 验证组合器
     * @var array
     */
    protected static array $globalValidatorCombiners = [];

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


    // 当前实例

    protected array $validatorCombiners = [];

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
        // 查看配置的
        return array_merge(static::$globalValidatorCombiners, $this->validatorCombiners);
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
        foreach ($this->validatorCombiners() as $validatorCombiner) {
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
