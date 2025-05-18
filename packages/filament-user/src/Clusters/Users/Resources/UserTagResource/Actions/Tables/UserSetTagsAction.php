<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Actions\Tables;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\Commands\UserSetTagsCommand;
use RedJasmine\User\Application\Services\UserApplicationService;

class UserSetTagsAction extends Action
{

    public static function getDefaultName() : ?string
    {
        return 'set-tags';
    }

    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-user::user.commands.set-tags'));


        $this->icon('heroicon-o-tag');
        $this->fillForm(function ($record) {
            return [
                'tags' => $record->tags->pluck('id')->toArray(),
            ];
        });

        $this->form(function ($record) {

            return [
                Forms\Components\Select::make('tags')
                                       ->multiple()
                                       ->label(__('red-jasmine-user::user.relations.tags'))
                                       ->relationship(
                                           name: 'tags',
                                           titleAttribute: 'name',
                                       )
                                       ->loadStateFromRelationshipsUsing(null) // 不进行从关联中获取数据
                                       ->dehydrated()
                                       ->saveRelationshipsUsing(null) // 不进行自动保存
                                       ->dehydrated()
                                       ->preload()
                                       ->default([]),

            ];
        });


        $this->action(function ($data, $record) {

            try {
                $command = UserSetTagsCommand::from($data);
                $command->setKey($record->getKey());
                app(UserApplicationService::class)->setTags($command);
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