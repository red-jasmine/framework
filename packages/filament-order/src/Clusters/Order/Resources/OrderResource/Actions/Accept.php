<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms\Components\TextInput;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Support\Exceptions\AbstractException;

trait Accept
{
    protected function setUp() : void
    {
        parent::setUp();


        $this->label(__('red-jasmine-order::order.actions.accept'));
        $this->icon('heroicon-o-arrow-right-circle');

        $this->visible(fn($record) => $record->isAllowShipping());


        $this->action(function ($data, $record) {


            try {
                app(OrderCommandService::class)->progress(OrderProgressCommand::from($data));
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
