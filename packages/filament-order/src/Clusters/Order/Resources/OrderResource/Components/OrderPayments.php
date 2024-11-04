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
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Models\OrderProduct;

class OrderPayments extends Component implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;


    public int $id;


    public function table(Table $table) : Table
    {

        return $table
            ->modelLabel(__('red-jasmine-order::payment.labels.order-payments'))
            ->query(OrderPayment::query()->where('order_id', $this->id))
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::payment.fields.id')),
                          TextColumn::make('order_id')->label(__('red-jasmine-order::payment.fields.order_id')),
                          TextColumn::make('refund_id')->label(__('red-jasmine-order::payment.fields.refund_id')),
                          TextColumn::make('amount_type')->label(__('red-jasmine-order::payment.fields.amount_type'))->useEnum(),
                          TextColumn::make('payment_amount')->label(__('red-jasmine-order::payment.fields.payment_amount')),
                          TextColumn::make('status')->label(__('red-jasmine-order::payment.fields.status'))->useEnum(),
                          TextColumn::make('payment_time')->label(__('red-jasmine-order::payment.fields.payment_time')),
                          TextColumn::make('payment_type')->label(__('red-jasmine-order::payment.fields.payment_type')),
                          TextColumn::make('payment_id')->label(__('red-jasmine-order::payment.fields.payment_id')),
                          TextColumn::make('payment_method')->label(__('red-jasmine-order::payment.fields.payment_method')),
                          TextColumn::make('payment_channel')->label(__('red-jasmine-order::payment.fields.payment_channel')),
                          TextColumn::make('payment_channel_no')->label(__('red-jasmine-order::payment.fields.payment_channel_no')),
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
