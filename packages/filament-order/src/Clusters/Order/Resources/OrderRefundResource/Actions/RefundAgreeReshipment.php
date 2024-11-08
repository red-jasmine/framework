<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReshipmentCommand;
use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Support\Exceptions\AbstractException;

trait RefundAgreeReshipment
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.actions.agree-reshipment'));

        $this->color('success');
        $this->icon('heroicon-o-check-badge');
        $this->visible(fn(OrderRefund $record) => $record->isAllowAgreeReshipment());
        $this->requiresConfirmation();


        $this->action(function ($data, $record) {

            $data['id'] = $record->id;

            try {
                app(RefundCommandService::class)->agreeReshipment(RefundAgreeReshipmentCommand::from($data));

            } catch (AbstractException $abstractException) {
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->sendFailureNotification();
                return $this->halt();
            }
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
