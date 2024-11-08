<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use RedJasmine\Order\Domain\Models\OrderLogistics as Model;

class OrderLogistics extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;

    public int $orderId;

    public ?string $entityType = null;

    public ?int $entityId = null;






    public function table(Table $table) : Table
    {

        return $table
            ->heading(__('red-jasmine-order::logistics.labels.order-logistics'))
            ->modelLabel(__('red-jasmine-order::logistics.labels.order-logistics'))
            ->query(Model::query()
                        ->where('order_id', $this->orderId)
                        ->when($this->entityType && $this->entityId, function ($query) {
                            $query->where('entity_type', $this->entityType)
                                  ->where('entity_id', $this->entityId);
                        })
            )
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::logistics.fields.id')),
                          TextColumn::make('order_id')->label(__('red-jasmine-order::common.fields.order_id'))->copyable(),
                          TextColumn::make('entity_type')->label(__('red-jasmine-order::common.fields.entity_type'))->useEnum(),
                          TextColumn::make('entity_id')->label(__('red-jasmine-order::common.fields.entity_id'))->copyable(),
                          TextColumn::make('order_product_id')->label(__('red-jasmine-order::common.fields.order_product_id')),
                          TextColumn::make('shipper')->label(__('red-jasmine-order::logistics.fields.shipper'))->useEnum(),
                          TextColumn::make('status')->label(__('red-jasmine-order::logistics.fields.status'))->useEnum(),
                          TextColumn::make('logistics_company_code')->label(__('red-jasmine-order::logistics.fields.logistics_company_code')),
                          TextColumn::make('logistics_no')->label(__('red-jasmine-order::logistics.fields.logistics_no')),
                          TextColumn::make('shipping_time')->label(__('red-jasmine-order::logistics.fields.shipping_time')),
                          TextColumn::make('collect_time')->label(__('red-jasmine-order::logistics.fields.collect_time')),
                          TextColumn::make('dispatch_time')->label(__('red-jasmine-order::logistics.fields.dispatch_time')),
                          TextColumn::make('signed_time')->label(__('red-jasmine-order::logistics.fields.signed_time')),
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
