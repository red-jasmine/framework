<?php

namespace RedJasmine\FilamentCore\Resources\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OperatorTable
{

    public static function configure(Table $table) : Table
    {
        $table->pushColumns([
            TextColumn::make('creator')
                      ->formatStateUsing(fn($state) => $state?->getNickname())
                      ->label(__('red-jasmine-support::support.creator'))
                      ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_type')
            //                          ->label(__('red-jasmine-support::support.creator_type'))
            //                          ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_id')
            //                          ->label(__('red-jasmine-support::support.creator_id'))
            //                          ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updater')
                      ->formatStateUsing(fn($state) => $state?->getNickname() ?? $state?->getId())
                      ->label(__('red-jasmine-support::support.updater'))
                      ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('updater_type')
            //                          ->label(__('red-jasmine-support::support.updater_type'))
            //                          ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('updater_id')
            //                          ->label(__('red-jasmine-support::support.updater_id'))
            //
            //                          ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                      ->label(__('red-jasmine-support::support.created_at'))
                      ->dateTime()
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                      ->label(__('red-jasmine-support::support.updated_at'))
                      ->dateTime()
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
        ]);

        return $table;
    }

}