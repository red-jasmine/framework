<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Mokhosh\FilamentRating\Components\Rating;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundStarCommand;

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

            $data['rid']    = $record->id;
            $commandService = app(RefundCommandService::class);
            $command        = RefundStarCommand::from($data);
            $commandService->star($command);
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
