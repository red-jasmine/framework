<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRemarksCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundCommandService;

trait RefundRemarks
{
    protected function setUp() : void
    {
        parent::setUp();


        $this->icon('heroicon-o-chat-bubble-bottom-center-text');
        $this->label(__('red-jasmine-order::refund.fields.seller_remarks'));


        $this->fillForm(fn($record) : array => [
            'remarks' => $record->info->seller_remarks
        ]);
        $this->form([
                        Forms\Components\Textarea::make('remarks')
                                                 ->rows(10)
                                                 ->label(__('red-jasmine-order::refund.fields.seller_remarks'))
                    ]);

        $this->action(function ($data, $record) {

            $data['id']          = $record->id;
            $refundCommandService = app(RefundCommandService::class);
            $refundCommandService->sellerRemarks(RefundRemarksCommand::from($data));
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
