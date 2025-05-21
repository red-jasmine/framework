<?php

namespace RedJasmine\FilamentCore\Forms\Fields;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Contracts\Support\Htmlable;

class Money extends Field
{


    protected string $view = 'red-jasmine-filament-core::forms.fields.money';


    protected function setUp() : void
    {
        parent::setUp();
        $this->schema(
            [
                Cluster::make([
                    Forms\Components\Select::make('currency')
                                           ->searchable()
                                           ->options(['CNY' => 'CNY']),
                    Forms\Components\TextInput::make('total')->numeric(),
                ])
                       ->required($this->isRequired())
                ,
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