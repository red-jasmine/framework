<?php

namespace RedJasmine\FilamentProduct\Forms\Components;

use Filament\Forms\Components\Select;
use Money\Currency;
use RedJasmine\Money\Data\Money;

class ProductCurrencySelect extends Select
{


    protected function setUp() : void
    {
        parent::setUp();

        $this->options($this->getProductCurrencyOptions());

        $this->default(config('red-jasmine-product.currency', 'CNY'));
    }

    public function getProductCurrencyOptions() : array
    {


        $allowedCurrencies = config('red-jasmine-product.currencies');

        $currencies      = Money::getCurrencies();
        $currenciesCodes = [];
        foreach ($allowedCurrencies as $code) {
            $currency                              = new Currency($code);
            $currenciesCodes[$currency->getCode()] =
                $currencies->getName($currency)
                .'('.
                $currencies->getSymbol($currency).')';

        }


        return $currenciesCodes;

    }

}