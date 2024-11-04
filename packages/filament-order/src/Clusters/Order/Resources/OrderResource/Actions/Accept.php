<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderRejectCommand;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Support\Exceptions\AbstractException;

trait Accept
{


    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::order.actions.' . $this->getName()));

        if($this->getName() === 'accept'){
            $this->color('success');
            $this->icon('heroicon-o-check-badge');
        }else{
            $this->color('danger');
            $this->icon('heroicon-o-x-circle');
        }


        $this->visible(fn($record) => $record->isAccepting());

        $this->action(function ($data, $record) {

            $data['id'] = $record->id;

            try {
                if ($this->getName() === 'accept') {
                    app(OrderCommandService::class)->accept(OrderAcceptCommand::from($data));
                } else {
                    app(OrderCommandService::class)->accept(OrderRejectCommand::from($data));
                }

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
