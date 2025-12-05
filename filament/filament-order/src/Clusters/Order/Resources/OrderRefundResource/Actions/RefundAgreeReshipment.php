<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\Exceptions\BaseException;

trait RefundAgreeReshipment
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.commands.agree-reshipment'));

        $this->color('success');
        $this->icon('heroicon-o-check-badge');
        $this->visible(fn(Refund $record) => $record->isAllowAgreeReshipment());
        $this->requiresConfirmation();


        $this->action(function ($data, $record) {

            $data['id'] = $record->id;

            try {
                app(RefundApplicationService::class)->agreeReshipment(RefundAgreeReshipmentCommand::from($data));

            } catch (BaseException $abstractException) {
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->sendFailureNotification();
                return $this->halt();
            }
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
