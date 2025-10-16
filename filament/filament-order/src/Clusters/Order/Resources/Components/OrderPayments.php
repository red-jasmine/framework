<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\Components;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
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

class OrderPayments extends Component implements HasTable, HasForms, HasActions
{

    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithForms;


    public string $orderNo;

    public ?string $entityType = null;

    public ?int $entityId = null;


    public function table(Table $table) : Table
    {

        return $table
            ->heading(__('red-jasmine-order::payment.labels.order-payments'))
            ->modelLabel(__('red-jasmine-order::payment.labels.order-payments'))
            ->query(OrderPayment::query()
                        ->where('order_no', $this->orderNo)
                        ->when($this->entityType && $this->entityId, function ($query) {
                            $query->where('entity_type', $this->entityType)
                                  ->where('entity_id', $this->entityId);
                        })
            )
            ->paginated(false)
            ->columns([
                          TextColumn::make('id')->label(__('red-jasmine-order::payment.fields.id'))->copyable(),
                          TextColumn::make('order_id')->label(__('red-jasmine-order::common.fields.order_id'))->copyable(),
                          TextColumn::make('entity_type')->label(__('red-jasmine-order::common.fields.entity_type'))->useEnum(),
                          TextColumn::make('entity_id')->label(__('red-jasmine-order::common.fields.entity_id'))->copyable(),
                          TextColumn::make('amount_type')->label(__('red-jasmine-order::payment.fields.amount_type'))->useEnum(),
                          TextColumn::make('payment_amount')->label(__('red-jasmine-order::payment.fields.payment_amount')),
                          TextColumn::make('status')->label(__('red-jasmine-order::payment.fields.status'))->useEnum()
                          ->tooltip(fn($record)=>$record->message)
                          ,
                          TextColumn::make('payment_time')->label(__('red-jasmine-order::payment.fields.payment_time')),
                          TextColumn::make('payment_type')->label(__('red-jasmine-order::payment.fields.payment_type')),
                          TextColumn::make('payment_id')->label(__('red-jasmine-order::payment.fields.payment_id'))->copyable(),
                          TextColumn::make('payment_method')->label(__('red-jasmine-order::payment.fields.payment_method')),
                          TextColumn::make('payment_channel')->label(__('red-jasmine-order::payment.fields.payment_channel')),
                          TextColumn::make('payment_channel_no')->label(__('red-jasmine-order::payment.fields.payment_channel_no'))->copyable(),
                      ])
            ->filters([
                          // ...
                      ])
            ->recordActions([


                      ])
            ->toolbarActions([
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
