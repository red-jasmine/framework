<?php

namespace RedJasmine\FilamentCore\Resources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\FusedGroup;

class Operators extends Fieldset
{

    protected function setUp() : void
    {
        parent::setUp();
        $this->label(__('red-jasmine-support::support.operators'));
        $this->visibleOn('view');
        $this->columns(1);
        $this->schema([

            FusedGroup::make([
                TextInput::make('creator_type')
                         ->label(__('red-jasmine-support::support.creator_type'))
                         ->maxLength(64)
                         ->visibleOn('view'),
                TextInput::make('creator_id')
                         ->label(__('red-jasmine-support::support.creator_id'))
                         ->prefix('ID')
                         ->visibleOn('view'),
                TextInput::make('created_at')

            ])->visibleOn('view')
                      ->label(__('red-jasmine-support::support.creator'))
                      ->columns(3),
            FusedGroup::make([
                TextInput::make('updater_type')
                         ->label(__('red-jasmine-support::support.updater_type'))
                         ->maxLength(64)
                         ->visibleOn('view'),
                TextInput::make('updater_id')
                         ->label(__('red-jasmine-support::support.updater_id'))
                         ->prefix('ID')
                         ->visibleOn('view'),
                TextInput::make('updated_at')
            ])
                      ->visibleOn('view')
                      ->label(__('red-jasmine-support::support.updater'))
                      ->columns(3),
        ]);
    }

}