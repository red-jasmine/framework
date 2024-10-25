<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms\Components\TextInput;
use Mokhosh\FilamentRating\Components\Rating;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderStarCommand;

trait OrderProductProgress
{
    protected function setUp() : void
    {
        parent::setUp();


        $this->label(__('red-jasmine-order::order.fields.product.progress'));
        $this->icon('heroicon-o-arrow-right-circle');

        // TODO 根据状态显示

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
            app(OrderCommandService::class)->progress(OrderProgressCommand::from($data));
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
