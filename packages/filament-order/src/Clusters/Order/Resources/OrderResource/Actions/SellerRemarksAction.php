<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms;

use Filament\Tables\Actions\Action;

class SellerRemarksAction extends Action
{

    protected function setUp() : void
    {
        parent::setUp();

        $this->label('卖家备注');
        $this->color('primary');
        $this->modalWidth('lg');
        $this->modalHeading('卖家备注');
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
