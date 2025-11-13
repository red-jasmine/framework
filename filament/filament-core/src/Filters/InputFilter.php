<?php

namespace RedJasmine\FilamentCore\Filters;

use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class InputFilter extends Filter
{

    protected function setUp() : void
    {
        parent::setUp(); 

        $this->schema([
                        TextInput::make('value')->label(fn() => $this->evaluate($this->label))


                    ]);

        $this->query(function (Builder $query, array $data) : Builder {
            return $query->when(Str::replace([ ',', '，', ' ' ], ',', $data['value'] ?? ''),
                fn(Builder $query, $value) : Builder => $query->whereIn($this->getName(), explode(',', $value)));
        });
    }


}
