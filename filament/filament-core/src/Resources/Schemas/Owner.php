<?php

namespace RedJasmine\FilamentCore\Resources\Schemas;

use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use RedJasmine\Support\Domain\Contracts\BelongsToOwnerInterface;

class Owner extends FusedGroup
{


    protected string $ownerKey = 'owner';

    protected function setUp() : void
    {
        $user  = auth()->user();
        $owner = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
        parent::setUp();

        $this->schema([
            TextInput::make($this->ownerKey.'_type')
                     //->prefix(__('red-jasmine-support::support.owner_type'))
                     ->label(__('red-jasmine-support::support.owner_type'))
                     ->default($owner->getType())
                     ->required()
                     ->maxLength(64)
                     ->live(),
            TextInput::make($this->ownerKey.'_id')
                     ->prefix('ID')
                     ->label(__('red-jasmine-support::support.owner_id'))
                     ->required()
                     ->default($owner->getID())
                     ->live(),

        ]);

        $this->label(__('red-jasmine-support::support.owner'));
        $this->columns(2);
    }

    public static function make(array|Closure $schema = [], string $ownerKey = 'owner', $disabled = false) : static
    {
        $static           = app(static::class, ['schema' => $schema,]);
        $static->ownerKey = $ownerKey;
        $static->configure();
        return $static;

    }
}