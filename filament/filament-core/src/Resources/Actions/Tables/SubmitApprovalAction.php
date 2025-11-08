<?php

namespace RedJasmine\FilamentCore\Resources\Actions\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Commands\ApprovalCommand;
use RedJasmine\Support\Application\Commands\SubmitApprovalCommand;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\AbstractException;

class SubmitApprovalAction extends Action
{


    protected string $serviceClass;

    protected function setUp() : void
    {
        parent::setUp();
        $this->label('发起审批')
             ->modalWidth('7xl')
             ->slideOver()
             ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-o-bell')
             ->form([
                 TextInput::make('message'),
             ])

             ->action(function (array $data, $record) {

                 try {

                     $service = app($this->serviceClass);
                     $command = SubmitApprovalCommand::from($data);
                     $command->setKey($record->getKey());
                     $result = $service->submitApproval($command);

                     Notification::make()->title('成功')->success()->send();
                 } catch (AbstractException $throwable) {
                     Notification::make()->title('失败')
                                 ->body($throwable->getMessage())
                                 ->warning()->send();
                 }


             })->visible(static function (Model $record) : bool {
                return  $record->canApproval();

            });


    }

    public function service($service) : SubmitApprovalAction
    {

        $this->serviceClass = $service;

        return $this;

    }
}