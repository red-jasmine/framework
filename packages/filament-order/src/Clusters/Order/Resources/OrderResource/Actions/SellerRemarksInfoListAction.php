<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms;
use Filament\Infolists\Components\Actions\Action;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;


class SellerRemarksInfoListAction extends Action
{

    protected function setUp() : void
    {

        $name = $this->name;


        parent::setUp();

        $this->label(__('red-jasmine-order::order.fields.' . $name));
        $this->icon('heroicon-o-chat-bubble-bottom-center-text');
//        $this->modalWidth('lg');
//        $this->modalHeading('卖家备注');
        $this->fillForm(fn($record) : array => [
            'remarks' => $record->info->{$this->name}
        ]);
        $this->form([
                        Forms\Components\Textarea::make('remarks')
                                                 ->rows(10)
                                                 ->label(__('red-jasmine-order::order.fields.' . $name))
                    ]);

        $this->action(function ($data, $record) {

            $data['id'] = $record->id;

            $command = OrderRemarksCommand::from($data);

            dd($this->name, $command);
        });
    }

}
