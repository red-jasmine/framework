<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Community\Application\Services\Topic\TopicApplicationService;
use RedJasmine\Community\Domain\Data\TopicData;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\FilamentCommunity\Clusters\Community;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class TopicResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $service       = TopicApplicationService::class;
    protected static ?string $createCommand = TopicData::class;
    protected static ?string $updateCommand = TopicData::class;


    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Community::class;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_type')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('owner_id')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('title')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->image(),
                Forms\Components\TextInput::make('description')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('keywords')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('status')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\Select::make('category_id')
                                       ->relationship('category', 'name'),
                Forms\Components\Toggle::make('is_top')
                                       ->required(),
                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\TextInput::make('approval_status')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('version')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\TextInput::make('creator_type')
                                          ->maxLength(32),
                Forms\Components\TextInput::make('creator_id')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('creator_nickname')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('creator_avatar')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_type')
                                          ->maxLength(32),
                Forms\Components\TextInput::make('updater_id')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_nickname')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_avatar')
                                          ->maxLength(64),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('owner_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('owner_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('title')
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('description')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('keywords')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('status')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_top')
                                         ->boolean(),
                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('approval_status')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('version')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('creator_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('creator_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('creator_nickname')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('creator_avatar')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_nickname')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_avatar')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit'   => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
