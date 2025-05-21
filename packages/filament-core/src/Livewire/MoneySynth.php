<?php

namespace RedJasmine\FilamentCore\Livewire;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Money\Currency;
use Money\Money;

class MoneySynth extends Synth
{

    public static $key = 'money';

    public static function match($target) : bool
    {
        return $target instanceof Money;
    }


    public function dehydrate($target)
    {
        return [$target->jsonSerialize(), []];
    }

    public function hydrate($value) : ?Money
    {
        return new Money($value['amount'], new Currency($value['currency']));
    }

}