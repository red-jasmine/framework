<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Mokhosh\FilamentRating\Components\Rating;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundStarCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;

trait RefundStar
{
    protected function setUp() : void
    {
        parent::setUp();


        $this->label(__('red-jasmine-order::refund.fields.star'));

        $this->icon('heroicon-o-star');
        $this->fillForm(fn($record) : array => [
            'star' => $record->star
        ]);
        $this->form([

                        Rating::make('star')
                              ->allowZero()
                              ->stars(10)
                              ->label(__('red-jasmine-order::refund.fields.star')),

                    ]);

        $this->action(function ($data, $record) {

            $data['id']    = $record->id;
            $commandService = app(RefundApplicationService::class);
            $command        = RefundStarCommand::from($data);
            $commandService->star($command);
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
