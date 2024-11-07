<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Domain\Models\OrderRefund;
use Throwable;


trait RefundAgree
{

    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.actions.agree'));

        $this->icon('heroicon-o-check-circle');
        $this->color('success');

        $this->visible(fn(OrderRefund $record) => $record->isAllowAgreeRefund());


        $this->fillForm(fn(OrderRefund $record) : array => [

            'amount'         => $record->refund_amount->value(),
            'freight_amount' => $record->freight_amount->value(),
        ]);
        $this->form([
                        Forms\Components\TextInput::make('amount')
                                                  ->numeric()
                                                  ->label(__('red-jasmine-order::refund.fields.refund_amount')),
                        Forms\Components\TextInput::make('freight_amount')
                                                  ->numeric()
                                                  ->label(__('red-jasmine-order::refund.fields.freight_amount'))
                    ]);


        $this->action(function ($data, $record) {

            $data['rid'] = $record->id;
            $command     = RefundAgreeRefundCommand::from($data);
            try {
                app(RefundCommandService::class)->agreeRefund($command);
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
