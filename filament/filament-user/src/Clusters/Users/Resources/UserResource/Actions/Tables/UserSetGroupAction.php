<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\Commands\UserSetGroupCommand;
use RedJasmine\User\Application\Services\UserApplicationService;

class UserSetGroupAction extends Action
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

    public static function getDefaultName(): ?string
    {
        return 'setGroup';
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->icon('heroicon-o-squares-plus');
        $this->authorize(static::getDefaultName());
        $this->label(label: __('red-jasmine-user::user.commands.set-group'));

        $this->fillForm(function ($record) {
            return [
                'group_id' => $record->group_id,
            ];
        });

        $this->form(function ($record) {

            return [

                SelectTree::make('group_id')
                          ->label(__('red-jasmine-user::user.relations.group'))
                          ->relationship(relationship: 'group', titleAttribute: 'name', parentAttribute: 'parent_id',
                          )
                          ->searchable()
                          ->default(null)
                          ->enableBranchNode()
                          ->parentNullValue(0)
                          ->dehydrateStateUsing(fn($state) => (int) $state),
            ];
        });


        $this->action(function ($data, $record) {

            try {
                $command = UserSetGroupCommand::from($data);
                $command->setKey($record->getKey());
                app($this->service)->setGroup($command);
            } catch (AbstractException $abstractException) {
                Notification::make()->danger()
                            ->title($abstractException->getMessage())
                            ->send();
                return;
            }


            Notification::make()->success()
                        ->title('OK')
                        ->send();

            return;
        });
    }
}