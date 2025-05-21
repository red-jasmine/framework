<?php

namespace RedJasmine\FilamentCore\Forms\Fields;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Contracts\Support\Htmlable;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

class Money extends Field
{


    protected string $view = 'red-jasmine-filament-core::forms.fields.money';


    protected function setUp() : void
    {
        parent::setUp();
        $this->afterStateHydrated(function (Money $component, $state) {

            if ($state instanceof \Money\Money) {
                $currencies     = new ISOCurrencies();
                $moneyFormatter = new DecimalMoneyFormatter($currencies);
                $data           = (array) ($state?->jsonSerialize());
                $data['amount'] = $moneyFormatter->format($state);
                $component->state($data);
            }

        });
        $this->afterStateUpdated(function ($component,$state,$old){


        });
        $this->dehydrateStateUsing(function (Money $component, $state) {

            if (is_array($state) && filled($state['amount'] ?? null) && filled($state['currency'] ?? null)) {
                $currency        = new \Money\Currency($state['currency']);
                $currencies      = new ISOCurrencies();
                $moneyParser     = new DecimalMoneyParser($currencies);
                $money           = $moneyParser->parse($state['amount'], $currency);
                $state['amount'] = $money->getAmount();

                $component->state($state);
            }

            return $state;
        });


        $this->schema(
            [
                Cluster::make([
                    Forms\Components\Select::make('currency')
                                           ->prefix('货币')
                                           ->columnSpan(2)
                                           ->options(['CNY' => 'CNY'])
                    ,
                    Forms\Components\TextInput::make('amount')
                                              ->columnSpan(3)
                                              ->numeric(),
                ])->columns(5)
                       ->name($this->name)

                // ,
            ]
        );

    }

    public function label(Htmlable|Closure|string|null $label) : static
    {
        parent::label($label);
        foreach ($this->getChildComponents() as $component) {

            $component->label($label);
        }
        return $this;
    }

    public function required(bool|Closure $condition = true) : static
    {
        parent::required($condition);
        foreach ($this->getChildComponents() as $component) {
            $component->required($condition);
            foreach ($component->getChildComponents() as $component1) {
                $component1->required($condition);
            }
        }
        return $this;
    }


}