<?php

namespace RedJasmine\FilamentCore\Filters;

use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class InputFilter extends Filter
{

    protected function setUp() : void
    {
        parent::setUp(); 

        $this->form([
                        Forms\Components\TextInput::make('value')->label(fn() => $this->evaluate($this->label))


                    ]);
        // TODO 是否允许多个
        $this->query(function (Builder $query, array $data) : Builder {
            return $query->when(Str::replace([ ',', ',', ' ' ], ',', $data['value'] ?? ''),
                fn(Builder $query, $value) : Builder => $query->whereIn($this->getName(), explode(',', $value)));
        });
    }


}
