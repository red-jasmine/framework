<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables;

use Filament\Forms;
use Filament\Tables\Actions\Action;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\Commands\UserSetStatusCommand;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;

class UserSetStatusAction extends Action
{



    protected string $service;

    public function getService() : string
    {
        return $this->service;
    }

    public function setService(string $service) : static
    {
        $this->service = $service;
        return $this;
    }


    public static function getDefaultName() : ?string
    {
        return 'setStatus';
    }

    protected function setUp() : void
    {

        parent::setUp();
        $this->authorize(static::getDefaultName());
        $this->icon('heroicon-o-cog');
        $this->label(label: __('red-jasmine-user::user.commands.set-status'));

        $this->fillForm(function ($record) {
            return [
                'status' => $record->status,
            ];
        });

        $this->form(function ($record) {

            return [

                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-user::user.fields.status'))
                                              ->inline()
                                              ->default(UserStatusEnum::ACTIVATED)
                                              ->useEnum(UserStatusEnum::class),
            ];
        });


        $this->action(function ($data, $record) {

            try {
                $command = UserSetStatusCommand::from($data);
                $command->setKey($record->getKey());
                app($this->service)->setStatus($command);
            } catch (AbstractException $abstractException) {
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->failure();

                return;
            }


            $this->successNotificationTitle(__('success'));

            $this->success();


            return;
        });
    }
}