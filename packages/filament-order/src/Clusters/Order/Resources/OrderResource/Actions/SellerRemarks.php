<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderMessageCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use Filament\Forms;

trait SellerRemarks
{
    protected function setUp() : void
    {
        parent::setUp();

        $name = $this->name;
        switch ($this->name) {
            case 'seller_message':
                $this->icon('heroicon-o-chat-bubble-bottom-center-text');
                break;
            case 'seller_remarks':
                $this->icon('heroicon-o-pencil-square');
                break;
        }
        $this->label(__('red-jasmine-order::order.fields.' . $name));


        $this->fillForm(fn($record) : array => [
            'content' => $record->info->{$this->name}
        ]);
        $this->form([
                        Forms\Components\Textarea::make('content')
                                                 ->rows(10)
                                                 ->label(__('red-jasmine-order::order.fields.' . $name))
                    ]);

        $this->action(function ($data, $record) {

            $data['id']          = $record->id;
            $orderCommandService = app(OrderCommandService::class);

            switch ($this->name) {
                case 'seller_message':
                    $data['message'] = $data['content'];
                    $command         = OrderMessageCommand::from($data);
                    $orderCommandService->sellerMessage($command);
                    break;
                case 'seller_remarks':
                    $data['remarks'] = $data['content'];
                    $command         = OrderRemarksCommand::from($data);
                    $orderCommandService->sellerRemarks($command);
                    break;
            }

            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
