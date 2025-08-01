<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use RedJasmine\Order\Domain\Models\OrderCardKey;

class OrderCardKeys extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;

    public string $orderNo;

    public ?string $entityType = null;

    public ?int $entityId = null;


    public function table(Table $table) : Table
    {

        return $table
            ->heading(__('red-jasmine-order::card-keys.labels.order-card-keys'))
            ->modelLabel(__('red-jasmine-order::card-keys.labels.order-card-keys'))
            ->query(OrderCardKey::query()
                                ->where('order_no', $this->order_no)
                                ->when($this->entityType && $this->entityId, function ($query) {
                            $query->where('entity_type', $this->entityType)
                                  ->where('entity_id', $this->entityId);
                        })

            )
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::card-keys.fields.id')),
                          TextColumn::make('order_id')->label(__('red-jasmine-order::common.fields.order_id'))->copyable(),
                          TextColumn::make('entity_type')->label(__('red-jasmine-order::common.fields.entity_type'))->useEnum(),
                          TextColumn::make('entity_id')->label(__('red-jasmine-order::common.fields.entity_id'))->copyable(),
                          TextColumn::make('order_product_id')->label(__('red-jasmine-order::card-keys.fields.order_product_id'))->copyable(),
                          TextColumn::make('quantity')->label(__('red-jasmine-order::card-keys.fields.quantity')),
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
