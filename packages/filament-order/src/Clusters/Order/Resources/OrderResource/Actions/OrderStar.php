<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Mokhosh\FilamentRating\Components\Rating;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderStarCommand;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;

trait OrderStar
{
    protected function setUp() : void
    {
        parent::setUp();

        $name = $this->name;

        $this->label(__('red-jasmine-order::order.fields.' . $name));

        $this->icon('heroicon-o-star');
        $this->fillForm(fn($record) : array => [
            'star' => $record->star
        ]);
        $this->form([

                        Rating::make('star')
                              ->allowZero()
                              ->stars(10)
                              ->label(__('red-jasmine-order::order.fields.' . $name)),

                    ]);

        $this->action(function ($data, $record) {

            $data['id']          = $record->id;
            $orderCommandService = app(OrderCommandService::class);
            $command             = OrderStarCommand::from($data);
            $orderCommandService->star($command);
            $this->successNotificationTitle('ok');
            $this->success();
        });
    }
}
