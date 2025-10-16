<?php

namespace RedJasmine\FilamentCore\Forms\Fields;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Cknow\Money\Money;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Filament\Support\RawJs;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Contracts\Support\Htmlable;
use Money\Currency;


class MoneyInput extends Field
{


    protected string $view = 'red-jasmine-filament-core::forms.fields.money';


    public static function getCurrenciesCodes() : array
    {
        $currencies      = Money::getCurrencies();
        $currenciesCodes = [];
        foreach ($currencies->getIterator() as $currency) {
            $currenciesCodes[$currency->getCode()] = $currencies->getSymbol($currency).' '.trans('money::currencies.'.$currency->getCode());
        }
        return $currenciesCodes;
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->afterStateHydrated(function (MoneyInput $component, $state) {

            if ($state instanceof Money) {
                $data['amount']   = $state->formatByDecimal();
                $data['currency'] = $state->getCurrency()->getCode();
                $component->state($data);
            }
            if (is_array($state) && filled($state['currency']) && filled($state['amount'])) {

                $money            = Money::parse($state['amount'], $state['currency']);
                $data['amount']   = $money->formatByDecimal();
                $data['currency'] = $money->getCurrency()->getCode();
                $component->state($data);
            }


        });

        $this->schema(
            [
                Cluster::make([
                    Select::make('currency')
                                           ->columnSpan(3)
                                           ->default(Money::getDefaultCurrency())
                                           ->options(static::getCurrenciesCodes())
                                           ->live()
                    ,
                    TextInput::make('amount')
                                              ->columnSpan(3)
                                              ->mask(function (Get $get, $state) {
                                                  if (filled($get('currency'))) {
                                                      $currencies = Money::getCurrencies();
                                                      $subunitFor = $currencies->subunitFor(new Currency($get('currency')));
                                                      return RawJs::make('$money($input,\'.\',\',\','.$subunitFor.')');
                                                  }
                                                  return null;
                                              })
                                              ->stripCharacters(',')
                                              ->mutateDehydratedStateUsing(function (Get $get, $state) : ?int {
                                                  if (filled($state) && filled($get('currency'))) {
                                                      $money = Money::parseByDecimal($state, $get('currency'));
                                                      return $money->getAmount();
                                                  }

                                                  return null;
                                              })
                                              ->numeric()
                    ,
                ])
                       ->columns(6)
                       ->name($this->name)

            ]
        );

    }

    public function label(Htmlable|Closure|string|null $label) : static
    {
        parent::label($label);
        foreach ($this->getDefaultChildComponents() as $component) {

            $component->label($label);
        }
        return $this;
    }

    public function required(bool|Closure $condition = true) : static
    {
        parent::required($condition);
        foreach ($this->getDefaultChildComponents() as $component) {
            $component->required($condition);
            foreach ($component->getChildComponents() as $component1) {
                $component1->required($condition);
            }
        }
        return $this;
    }

    public function hiddenLabel(bool|Closure $condition = true) : static
    {
        parent::required($condition);
        foreach ($this->getDefaultChildComponents() as $component) {
            $component->hiddenLabel($condition);
        }
        return $this;
    }


}