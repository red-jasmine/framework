<?php

namespace RedJasmine\FilamentCore\Schemas;

use Filament\Forms\Components\TextInput;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use Filament\Forms;
trait HasOwnerSchemas
{
    public static function ownerFormSchemas(string $name = 'owner') : array
    {
        $user     = auth()->user();
        $owner    = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
        $disabled = true;
        if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            $disabled = false;
        }
        return [
            TextInput::make($name.'_type')
                                      ->label(__('red-jasmine-support::support.owner_type'))
                                      ->default($owner->getType())
                                      ->required()
                                      ->maxLength(64)
                                      ->disabled($disabled)
                                      ->live(),
            TextInput::make($name.'_id')
                                      ->label(__('red-jasmine-support::support.owner_id'))
                                      ->required()
                                      ->default($owner->getID())
                                      ->disabled($disabled)
                                      ->live(),

        ];


    }
}