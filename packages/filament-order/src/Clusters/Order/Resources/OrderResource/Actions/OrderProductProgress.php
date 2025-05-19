<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms\Components\TextInput;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Support\Exceptions\AbstractException;

trait OrderProductProgress
{
    protected function setUp() : void
    {
        parent::setUp();


        $this->label(__('red-jasmine-order::order.fields.product.progress'));
        $this->icon('heroicon-o-arrow-right-circle');

        $this->visible(fn($record)=>$record->isAllowSetProgress());

        $this->fillForm(fn($record) : array => [
            'progress'       => $record->progress,
            'progress_total' => $record->progress_total,
        ]);
        $this->form(fn($record) : array => [
            TextInput::make('progress')
                     ->minValue(0)
                     ->numeric()
                     ->suffix($record->progress_total)
                     ->label(__('red-jasmine-order::order.fields.product.progress')),

        ]);

        $this->action(function ($data, $record) {

            $data['id']             = $record->order_id;
            $data['orderProductId'] = $record->id;
            try {
                app(OrderApplicationService::class)->progress(OrderProgressCommand::from($data));
            }catch (AbstractException $abstractException){
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->sendFailureNotification();
                return $this->halt();
            }

            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
