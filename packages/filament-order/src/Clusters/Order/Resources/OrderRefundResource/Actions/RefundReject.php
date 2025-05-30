<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRejectCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Refund;
use Throwable;

trait RefundReject
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.actions.reject'));

        $this->icon('heroicon-o-minus-circle');
        $this->color('danger');

        $this->visible(fn(Refund $record) => $record->isAllowReject());

        $this->fillForm([
                'reason' => ''
            ]
        );
        $this->form([
            Forms\Components\TextInput::make('reason')
                                      ->label(__('red-jasmine-order::refund.fields.reason')),

        ]);

        $this->action(function ($data, $record) {

            $data['refund_no'] = $record->refund_no;
            $data['reason']    = $data['reason'] ?? '';
            $command           = RefundRejectCommand::from($data);
            try {
                app(RefundApplicationService::class)->reject($command);
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
