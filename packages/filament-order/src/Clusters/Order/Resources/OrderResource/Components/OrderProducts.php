<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions\Table\OrderProductProgressTableAction;
use RedJasmine\Order\Domain\Models\OrderProduct;

class OrderProducts extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;


    public $id;


    public function table(Table $table) : Table
    {

        return $table
            ->heading(__('red-jasmine-order::order.labels.products'))
            ->modelLabel(__('red-jasmine-order::order.labels.products'))
            ->query(OrderProduct::query()->where('order_id', $this->id))
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::order.fields.product.id')),
                          ImageColumn::make('image')->label(__('red-jasmine-order::order.fields.product.image')),
                          TextColumn::make('title')->label(__('red-jasmine-order::order.fields.product.title')),
                          TextColumn::make('sku_name')->label(__('red-jasmine-order::order.fields.product.sku_name')),
                          TextColumn::make('product_id')->label(__('red-jasmine-order::order.fields.product.product_id'))->toggleable(isToggledHiddenByDefault: true),
                          TextColumn::make('sku_id')->label(__('red-jasmine-order::order.fields.product.sku_id'))->toggleable(isToggledHiddenByDefault: true),
                          TextColumn::make('price')->label(__('red-jasmine-order::order.fields.product.price'))->money('CNY'),
                          TextColumn::make('quantity')->label(__('red-jasmine-order::order.fields.product.quantity')),
                          TextColumn::make('unit')->label(__('red-jasmine-order::order.fields.product.unit'))->toggleable(isToggledHiddenByDefault: true),
                          TextColumn::make('unit_quantity')->label(__('red-jasmine-order::order.fields.product.unit_quantity'))->toggleable(isToggledHiddenByDefault: true),
                          TextColumn::make('product_amount')->label(__('red-jasmine-order::order.fields.product.product_amount')),
                          TextColumn::make('tax_amount')->label(__('red-jasmine-order::order.fields.product.tax_amount')),
                          TextColumn::make('discount_amount')->label(__('red-jasmine-order::order.fields.discount_amount')),
                          TextColumn::make('payable_amount')->label(__('red-jasmine-order::order.fields.product.payable_amount')),
                          TextColumn::make('progress')->label(__('red-jasmine-order::order.fields.product.progress')),
                          TextColumn::make('progress_total')->label(__('red-jasmine-order::order.fields.product.progress_total')),
                          TextColumn::make('order_status')->useEnum()->label(__('red-jasmine-order::order.fields.order_status')),
                          TextColumn::make('shipping_status')->useEnum()->toggleable(isToggledHiddenByDefault: true)->label(__('red-jasmine-order::order.fields.shipping_status')),
                          TextColumn::make('refund_status')->useEnum()->toggleable(isToggledHiddenByDefault: true)->label(__('red-jasmine-order::order.fields.refund_status')),
                          TextColumn::make('refund_amount')->toggleable(isToggledHiddenByDefault: true)->label(__('red-jasmine-order::order.fields.refund_amount')),
                      ])
            ->filters([
                          // ...
                      ])
            ->actions([

                OrderProductProgressTableAction::make('progress'),
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
