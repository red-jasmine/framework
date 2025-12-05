<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\Commands\SetAccount\UserSetAccountCommand;

class UserSetAccountAction extends Action
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
        return 'setAccount';
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->authorize(static::getDefaultName());
        $this->icon('heroicon-o-shield-check');
        $this->label(label: __('red-jasmine-user::user.commands.set-account'));

        $this->fillForm(function ($record) {
            return [
                'name'  => $record->name,
                'phone' => $record->phone,
                'email' => $record->email,
            ];
        });

        $this->form(function ($record) {

            return [
                TextInput::make('name')
                                          ->label(__('red-jasmine-user::user.fields.name'))
                                          ->required()
                                          ->maxLength(64)

                ,
                TextInput::make('phone')
                                          ->label(__('red-jasmine-user::user.fields.phone'))
                                          ->maxLength(64)

                ,
                TextInput::make('email')
                                          ->label(__('red-jasmine-user::user.fields.email'))
                                          ->email()
                                          ->maxLength(255)
                ,

            ];
        });


        $this->action(function ($data, $record) {

            try {
                $command = UserSetAccountCommand::from($data);
                $command->setKey($record->getKey());
                app($this->service)->setAccount($command);
            } catch (BaseException $abstractException) {
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->failure();

                return;
            }
            $this->successNotificationTitle(__('success'));
            $this->success();
        });
    }
}