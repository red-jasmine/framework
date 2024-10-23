<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * @property Form $form
 */
class Shipping extends Page
{


    protected static string $resource = OrderResource::class;
    use InteractsWithRecord, ResourcePageHelper {
        InteractsWithRecord::resolveRecord insteadof ResourcePageHelper;
    }

    public ?array $data = [];


    use InteractsWithFormActions;

    public function mount(int|string $record) : void
    {

        $this->record = $this->resolveRecord($record);


        $this->form->fill([
                              'order_products' => $this->record->products->pluck('id')->toArray(),
                              'is_finished'    => true,
                          ]);


    }

    protected function getFormActions() : array
    {
        return [
            $this->getSaveFormAction(),

        ];
    }


    public function save()
    {
        //$data = $this->form->getState();
        dd($this->form->getState());


    }


    public function dummy()
    {
        $data       = $this->form->getState();
        $data['id'] = $this->record->id;
        $command    = OrderDummyShippingCommand::from($data);


        try {
            app(static::getResource()::getCommandService())->dummyShipping($command);
        }catch (AbstractException $abstractException){
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

    protected function getSaveFormAction() : Action
    {
        return Action::make('dummy')
                     ->label('发货')
                     ->submit('dummy');
    }


    protected static string $view = 'red-jasmine-filament-order::resources.order-resource.pages.shipping';

    public function getHeading() : string
    {
        return 'getHeading';
    }

    public static function getNavigationLabel() : string
    {
        return '大号';
    }

    public function getTitle() : string|Htmlable
    {
        return '这个啥z';
    }


    public function form(Form $form) : Form
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
                    ->statePath('data')
                    ->columns(1);
    }

    protected function getHeaderActions() : array
    {
        return [
            Action::make('test')->button(),
            Action::make('test')->button(),
            Action::make('test')->button(),
            Action::make('test')->button(),

        ];

    }
}
