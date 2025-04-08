<?php

namespace RedJasmine\FilamentCore\Resources\Actions\Tables;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Commands\ApprovalCommand;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\AbstractException;

class ApprovalAction extends Action
{


    protected string $serviceClass;

    protected function setUp() : void
    {
        parent::setUp();
        $this->label('审批')
             ->modalWidth('7xl')
             ->slideOver()
             ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-o-bell')
             ->form([
                 Forms\Components\TextInput::make('message'),
                 Forms\Components\ToggleButtons::make('approval_status')
                                               ->required()
                                               ->label(__('red-jasmine-support::support.fields.approval_status'))
                                               ->inline()
                                               ->default(ApprovalStatusEnum::PASS->value)
                                               ->useEnum(ApprovalStatusEnum::class),

             ])
             ->fillForm(function ($record) : array {

                 return [
                     'id'              => $record->id,
                     'approval_status' => ApprovalStatusEnum::PASS->value
                 ];
             })
             ->action(function (array $data, $record) {

                 try {

                     $service = app($this->serviceClass);
                     $command = ApprovalCommand::from($data);
                     $command->setKey($record->getKey());
                     $result = $service->approval($command);

                     Notification::make()->title('成功')->success()->send();
                 } catch (AbstractException $throwable) {
                     Notification::make()->title('失败')
                                 ->body($throwable->getMessage())
                                 ->warning()->send();
                 }


             })->visible(static function (Model $record) : bool {
                return  $record->isAllowApproval();

            });


    }

    public function service($service) : ApprovalAction
    {

        $this->serviceClass = $service;

        return $this;

    }
}