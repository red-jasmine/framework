<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use RedJasmine\Order\Domain\Models\OrderProductCardKey;

class OrderCardKeys extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;


    public int $id;


    public function table(Table $table) : Table
    {

        return $table
            ->heading(__('red-jasmine-order::card-keys.labels.order-card-keys'))
            ->modelLabel(__('red-jasmine-order::card-keys.labels.order-card-keys'))
            ->query(OrderProductCardKey::query()->where('order_id', $this->id))
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::card-keys.fields.id')),
                          TextColumn::make('order_id')->label(__('red-jasmine-order::card-keys.fields.order_id')),
                          TextColumn::make('order_product_id')->label(__('red-jasmine-order::card-keys.fields.order_product_id')),
                          TextColumn::make('num')->label(__('red-jasmine-order::card-keys.fields.num')),
                          TextColumn::make('content_type')->label(__('red-jasmine-order::card-keys.fields.content_type'))->useEnum(),
                          TextColumn::make('content')->label(__('red-jasmine-order::card-keys.fields.content'))->copyable(),
                          TextColumn::make('source_type')->label(__('red-jasmine-order::card-keys.fields.source_type')),
                          TextColumn::make('source_id')->label(__('red-jasmine-order::card-keys.fields.source_id')),
                          TextColumn::make('status')->label(__('red-jasmine-order::card-keys.fields.status'))->useEnum(),
                      ])
            ->filters([
                          // ...
                      ])
            ->actions([


                      ])
            ->bulkActions([
                              // ...
                          ]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table}}
        </div>
        HTML;
    }
}
