<?php

namespace RedJasmine\FilamentOrder\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use RedJasmine\Order\Domain\Models\OrderProduct;

class OrderProducts extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;


    public $id;


    public function table(Table $table) : Table
    {

        return $table
            ->query(OrderProduct::query()->where('order_id', $this->id))
            ->paginated(false)
            ->columns([
                          TextColumn::make('id'),
                          ImageColumn::make('image'),
                          TextColumn::make('title'),
                          TextColumn::make('sku_name'),
                          TextColumn::make('product_id')->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('sku_id')->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('price')->money('CNY'),
                          TextColumn::make('num'),
                          TextColumn::make('unit')->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('unit_quantity')->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('product_amount'),
                          TextColumn::make('tax_amount'),
                          TextColumn::make('discount_amount'),
                          TextColumn::make('payable_amount'),
                          TextColumn::make('order_status')->useEnum(),
                          TextColumn::make('shipping_status')->useEnum()->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('refund_status')->useEnum()->toggleable(isToggledHiddenByDefault:true),
                          TextColumn::make('refund_amount')->toggleable(isToggledHiddenByDefault:true),
                      ])
            ->filters([
                          // ...
                      ])
            ->actions([
                          // ...
                      ])
            ->bulkActions([
                              // ...
                          ]);
    }


    public function render()
    {
        return view('red-jasmine-filament-order::livewire.order-products');
    }
}
