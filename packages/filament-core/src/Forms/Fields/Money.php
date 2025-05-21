<?php

namespace RedJasmine\FilamentCore\Forms\Fields;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Filament\Support\RawJs;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Contracts\Support\Htmlable;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use function Clue\StreamFilter\fun;

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

        $this->schema(
            [
                // TODO 货币配置化
                Cluster::make([
                    Forms\Components\Select::make('currency')
                                           ->prefix('货币')
                                           ->default('CNY')
                                           ->columnSpan(2)
                                           ->options(['CNY' => 'CNY'])
                                           ->live()
                    ,
                    Forms\Components\TextInput::make('amount')
                                              ->prefix('金额')
                                              ->columnSpan(3)
                                              ->mask(function (Forms\Get $get, $state) {
                                                  if (filled($get('currency'))) {
                                                      $currencies = new ISOCurrencies();
                                                      $subunit    = $currencies->subunitFor(new Currency($get('currency')));

                                                      return RawJs::make('$money($input,\'.\',\',\','.$subunit.')');
                                                  }
                                                  return RawJs::make('$money($input)');
                                              })
                                              ->stripCharacters(',')
                                              ->mutateDehydratedStateUsing(function (Forms\Get $get, $state) : ?int {
                                                  if (filled($state) && filled($get('currency'))) {
                                                      $currency    = new Currency($get('currency'));
                                                      $currencies  = new ISOCurrencies();
                                                      $moneyParser = new DecimalMoneyParser($currencies);
                                                      $money       = $moneyParser->parse($state, $currency);
                                                      return $money->getAmount();
                                                  }

                                                  return null;
                                              })
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