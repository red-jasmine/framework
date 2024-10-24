<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms;
use Filament\Infolists\Components\Actions\Action;


class SellerRemarksInfoListAction extends Action
{

    protected function setUp() : void
    {

        $name = $this->name;


        parent::setUp();

        $this->label(__('red-jasmine-order::order.fields.'.$name));

//        $this->modalWidth('lg');
//        $this->modalHeading('卖家备注');
        $this->fillForm(fn($record) : array => [
            'remarks' => $record->info->{$this->name}
        ]);
        $this->form([
                        Forms\Components\Textarea::make('remarks')
                    ]);

        $this->action(function ($data, $record) {


            dd($this->name, $data, $record);
        });
    }

}
