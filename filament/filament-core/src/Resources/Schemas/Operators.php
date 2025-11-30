<?php

namespace RedJasmine\FilamentCore\Resources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\FusedGroup;

class Operators extends Fieldset
{

    protected function setUp() : void
    {
        parent::setUp();
        $this->label(__('red-jasmine-support::support.operators'));
        $this->visibleOn('view');
        $this->schema([
            TextInput::make('creator_type')
                     ->label(__('red-jasmine-support::support.creator_type'))
                     ->maxLength(64)
                     ->visibleOn('view'),
            TextInput::make('creator_id')
                     ->label(__('red-jasmine-support::support.creator_id'))
                     ->required()
                     ->visibleOn('view'),
            TextInput::make('updater_type')
                     ->label(__('red-jasmine-support::support.updater_type'))
                     ->maxLength(64)
                     ->visibleOn('view'),
            TextInput::make('updater_id')
                     ->label(__('red-jasmine-support::support.updater_id'))
                     ->visibleOn('view'),
        ]);
    }

}