<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables;

use Filament\Forms;
use Filament\Tables\Actions\Action;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\Commands\UserSetAccountCommand;
use RedJasmine\User\Application\Services\Commands\UserSetStatusCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Enums\UserStatusEnum;

class UserSetAccountAction extends Action
{
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
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-user::user.fields.name'))
                                          ->required()
                                          ->maxLength(64)

                ,
                Forms\Components\TextInput::make('phone')
                                          ->label(__('red-jasmine-user::user.fields.phone'))
                                          ->maxLength(64)

                ,
                Forms\Components\TextInput::make('email')
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
                app(UserApplicationService::class)->setAccount($command);
            } catch (AbstractException $abstractException) {
                $this->failureNotificationTitle($abstractException->getMessage());
                $this->failure();

                return;
            }
            $this->successNotificationTitle(__('success'));
            $this->success();
        });
    }
}