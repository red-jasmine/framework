<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms\Components\TextInput;
use Filament\Forms;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Refund;
use Throwable;


trait RefundAgree
{

    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.commands.agree'));

        $this->icon('heroicon-o-check-circle');
        $this->color('success');

        $this->visible(fn(Refund $record) => $record->isAllowAgreeRefund());


        $this->fillForm(fn(Refund $record) : array => [

            'amount'         => $record->refund_amount->value(),
            'freight_amount' => $record->freight_amount->value(),
        ]);
        $this->form([
                        TextInput::make('amount')
                                                  ->numeric()
                                                  ->label(__('red-jasmine-order::refund.fields.refund_amount')),
                        TextInput::make('freight_amount')
                                                  ->numeric()
                                                  ->label(__('red-jasmine-order::refund.fields.freight_amount'))
                    ]);


        $this->action(function ($data, $record) {

            $data['id'] = $record->id;
            $command     = RefundAgreeRefundCommand::from($data);
            try {
                app(RefundApplicationService::class)->agreeRefund($command);
            } catch (Throwable $throwable) {
                $this->failureNotificationTitle($throwable->getMessage());
                $this->sendFailureNotification();
                $this->halt();
                return;
            }

            $this->successNotificationTitle('ok');
            $this->success();
        });
    }

}
