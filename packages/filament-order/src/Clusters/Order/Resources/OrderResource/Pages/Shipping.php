<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderLogisticsShippingCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * @property Form $dummy
 * @property Form $logistics
 */
class Shipping extends Page
{

    protected static string $resource = OrderResource::class;


    use InteractsWithRecord, ResourcePageHelper {
        InteractsWithRecord::resolveRecord insteadof ResourcePageHelper;
    }

    public ?array $data = [];
    protected function getViewData(): array
    {
        return [
            'forms'=>$this->getForms()
        ];
    }

    use InteractsWithFormActions;
    public function infolist(Infolist $infolist): Infolist
    {
        return static::getResource()::infolist($infolist);
    }

    protected function makeInfolist(): Infolist
    {
        return parent::makeInfolist()
                     ->record($this->getRecord())
                     ->columns($this->hasInlineLabels() ? 1 : 2)
                     ->inlineLabel($this->hasInlineLabels());
    }
    protected function hasInfolist(): bool
    {
        return (bool) count($this->getInfolist('infolist')->getComponents());
    }


    public function mount(int|string $record) : void
    {

        $this->record = $this->resolveRecord($record);


        $this->dummy->fill([
                              'order_products' => $this->record->products->pluck('id')->toArray(),
                              'is_finished'    => true,
                          ]);


        $this->logistics->fill([
            'is_split'=>false,

                          ]);
    }

    protected function getFormActions() : array
    {
        return [
            $this->getSaveFormAction(),

        ];
    }





    protected function getSaveFormAction() : Action
    {
        return Action::make('save')
                     ->label('发货')
                     ->submit('save');
    }


    protected static string $view = 'red-jasmine-filament-order::resources.order-resource.pages.shipping';

    public function getHeading() : string
    {
        return $this->getRecord()->id;
    }



    public function getTitle() : string|Htmlable
    {
        return $this->getRecord()->id;
    }


    protected function getForms() : array
    {
        return [
            'dummy',
            'logistics',
        ];
    }

    public function dummy(Form $form) : Form
    {
        $record = $this->record;
        return $form->schema([

                                 Forms\Components\CheckboxList::make('order_products')
                                                              ->options($record->products->pluck('title', 'id')->toArray()),

                                 Forms\Components\ToggleButtons::make('is_finished')
                                                               ->default(true)
                                                               ->grouped()
                                                               ->boolean()

                             ])
                    ->statePath('data.dummy')
                    ->columns(1);
    }

    public function dummySubmit()
    {
        $data       = $this->dummy->getState();
        $data['id'] = $this->record->id;
        $command    = OrderDummyShippingCommand::from($data);


        try {
            app(static::getResource()::getCommandService())->dummyShipping($command);
        } catch (AbstractException $abstractException) {
            Notification::make()->danger()
                        ->title($abstractException->getMessage())
                        ->send();
            return;
        }
        Notification::make()->success()
                    ->title('OK')
                    ->send();

        $this->redirect(static::getResource()::getUrl('index'));

    }

    public function logistics(Form $form) : Form
    {

        $record = $this->record;
        return $form->schema([

                                 Forms\Components\ToggleButtons::make('is_split')
                                                               ->default(false)
                                                               ->grouped()
                                                               ->boolean(),
                                 Forms\Components\CheckboxList::make('order_products')
                                                              ->options($record->products->pluck('title', 'id')->toArray()),

                                 Forms\Components\TextInput::make('express_company_code')->required(),
                                 Forms\Components\TextInput::make('express_no')->required(),

                             ])
                    ->statePath('data.logistics');
    }
    public function logisticsSubmit()
    {
        $data       = $this->logistics->getState();

        $data['id'] = $this->record->id;
        $command    = OrderLogisticsShippingCommand::from($data);
        try {
            app(static::getResource()::getCommandService())->logisticsShipping($command);
        } catch (AbstractException $abstractException) {
            Notification::make()->danger()
                        ->title($abstractException->getMessage())
                        ->send();
            return;
        }
        Notification::make()->success()
                    ->title('OK')
                    ->send();

        $this->redirect(static::getResource()::getUrl('index'));

    }

    protected function getHeaderActions() : array
    {
        return [


        ];

    }
}
