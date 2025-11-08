<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Community\Application\Services\Topic\TopicApplicationService;
use RedJasmine\Community\Domain\Data\TopicData;
use RedJasmine\Community\Domain\Models\Enums\TopicStatusEnum;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\FilamentCommunity\Clusters\Community;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages\CreateTopic;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages\EditTopic;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages\ListTopics;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\Actions\Tables\ApprovalAction;
use RedJasmine\FilamentCore\Resources\Actions\Tables\SubmitApprovalAction;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentCore\Resources\Schemas\Owner;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;

class TopicResource extends Resource
{
    use ResourcePageHelper;

    protected static ?string $service       = TopicApplicationService::class;
    protected static ?string $createCommand = TopicData::class;
    protected static ?string $updateCommand = TopicData::class;


    protected static ?string $model = Topic::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $cluster = Community::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-community::topic.labels.topic');
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['extension'];
        return $findQuery;
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([

                Flex::make([
                    Section::make([
                        TextInput::make('title')
                                                  ->label(__('red-jasmine-community::topic.fields.title'))
                                                  ->required()
                                                  ->maxLength(255),
                        FileUpload::make('image')
                                                   ->label(__('red-jasmine-community::topic.fields.image'))
                                                   ->image(),
                        TextInput::make('description')
                                                  ->label(__('red-jasmine-community::topic.fields.description'))
                                                  ->maxLength(255),
                        TagsInput::make('keywords')
                                                  ->label(__('red-jasmine-community::topic.fields.keywords'))
                                                  ->nestedRecursiveRules([
                                                      'min:1',
                                                      'max:100',
                                                  ])
                                                  ->reorderable()
                                                  ->separator(' '),

                        ToggleButtons::make('content_type')
                                                      ->label(__('red-jasmine-community::topic.fields.content_type'))
                                                      ->required()
                                                      ->inline()
                                                      ->live()
                                                      ->default(ContentTypeEnum::RICH)
                                                      ->useEnum(ContentTypeEnum::class),


                        RichEditor::make('content')
                                                   ->visible(fn(Get $get
                                                   ) : bool => $get('content_type') === ContentTypeEnum::RICH)
                                                   ->required()->label(__('red-jasmine-community::topic.fields.content')),
                        MarkdownEditor::make('content')
                                                       ->visible(fn(Get $get
                                                       ) : bool => $get('content_type') === ContentTypeEnum::MARKDOWN)
                                                       ->required()->label(__('red-jasmine-community::topic.fields.content')),

                        Textarea::make('content')
                                                 ->visible(fn(Get $get
                                                 ) : bool => $get('content_type') === ContentTypeEnum::TEXT)
                                                 ->required()->label(__('red-jasmine-community::topic.fields.content')),


                    ]),
                    Section::make([
                        Owner::make(),


                        SelectTree::make('category_id')
                                  ->label(__('red-jasmine-community::topic.fields.category_id'))
                                  ->relationship(relationship: 'category', titleAttribute: 'name', parentAttribute: 'parent_id',
                                  )
                                  ->searchable()
                                  ->default(null)
                                  ->enableBranchNode()
                                  ->parentNullValue(0)
                                  ->dehydrateStateUsing(fn($state) => (int) $state),
                        
                        Select::make('tags')
                                               ->multiple()
                                               ->label(__('red-jasmine-community::topic.fields.tags'))
                                               ->relationship(
                                                   name: 'tags',
                                                   titleAttribute: 'name',
                                               )
                                               ->loadStateFromRelationshipsUsing(null) // 不进行从关联中获取数据
                                               ->afterStateHydrated(function (?Model $record, Component $component, $state) {
                                if ($record) {
                                    $component->state($record->tags?->pluck('id')
                                                                   ->map(static fn($key) : string => (string) $key)
                                                                   ->toArray());
                                }

                            })
                                               ->saveRelationshipsUsing(null) // 不进行自动保存
                                               ->preload()
                                               ->dehydrated()
                                               ->default([])
                        ,

                        Toggle::make('is_top')
                                               ->label(__('red-jasmine-community::topic.fields.is_top'))
                                               ->required()
                                               ->default(false),
                        Toggle::make('is_show')
                                               ->label(__('red-jasmine-community::topic.fields.is_show'))
                                               ->required()
                                               ->default(true),
                        TextInput::make('sort')
                                                  ->label(__('red-jasmine-community::topic.fields.sort'))
                                                  ->required()
                                                  ->numeric()
                                                  ->default(0)
                                                  ->maxLength(255),
                        ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-community::topic.fields.status'))
                                                      ->required()
                                                      ->inline()
                                                      ->disabled()
                                                      ->default(TopicStatusEnum::DRAFT)
                                                      ->useEnum(TopicStatusEnum::class),
                        Select::make('approval_status')
                                               ->label(__('red-jasmine-support::support.fields.approval_status'))
                                               ->disabled()
                                               ->useEnum(ApprovalStatusEnum::class),


                    ])->grow(false),
                ])->columnSpanFull(),


                Operators::make(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label(__('red-jasmine-community::topic.fields.id'))
                                         ->sortable(),
                ...static::ownerTableColumns(),
                TextColumn::make('title')
                                         ->searchable()
                                         ->label(__('red-jasmine-community::topic.fields.title')),
                ImageColumn::make('image')
                                          ->label(__('red-jasmine-community::topic.fields.image')),
                TextColumn::make('description')
                                         ->label(__('red-jasmine-community::topic.fields.description'))
                                         ->searchable(),
                TextColumn::make('keywords')
                                         ->label(__('red-jasmine-community::topic.fields.keywords'))
                                         ->searchable(),
                TextColumn::make('category.name')
                                         ->label(__('red-jasmine-community::topic.fields.category'))
                                         ->numeric()
                                         ->sortable(),
                IconColumn::make('is_top')
                                         ->label(__('red-jasmine-community::topic.fields.is_top'))
                                         ->boolean(),
                TextColumn::make('sort')
                                         ->label(__('red-jasmine-community::topic.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                IconColumn::make('is_show')
                                         ->label(__('red-jasmine-community::topic.fields.is_show'))
                                         ->boolean(),
                TextColumn::make('status')
                                         ->label(__('red-jasmine-community::topic.fields.status'))
                                         ->useEnum(),
                TextColumn::make('approval_status')
                                         ->useEnum()
                                         ->label(__('red-jasmine-community::topic.fields.approval_status'))
                ,
                

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make('approval')
                                                                                ->service(static::$service),
                SubmitApprovalAction::make('submit-approval')
                                                                                      ->service(static::$service),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index'  => ListTopics::route('/'),
            'create' => CreateTopic::route('/create'),
            'edit'   => EditTopic::route('/{record}/edit'),
        ];
    }
}
