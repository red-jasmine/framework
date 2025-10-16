<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages\ListArticles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages\CreateArticle;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages\EditArticle;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Application\Services\Article\Commands\ArticlePublishCommand;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\Actions\Tables\ApprovalAction;
use RedJasmine\FilamentCore\Resources\Actions\Tables\SubmitApprovalAction;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;

class ArticleResource extends Resource
{

    use ResourcePageHelper;

    /**
     * @var class-string<ProductApplicationService::class>
     */
    protected static string $service       = ArticleApplicationService::class;
    protected static ?string $createCommand = ArticleData::class;
    protected static ?string $updateCommand = ArticleData::class;
    protected static bool    $onlyOwner     = true;


    protected static ?string $model = Article::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-c-document-text';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article.labels.title');
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['extension', 'tags'];
        return $findQuery;
    }


    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([

                Flex::make([
                    Section::make([
                        TextInput::make('title')
                                                  ->label(__('red-jasmine-article::article.fields.title'))
                                                  ->required()
                                                  ->maxLength(255),
                        FileUpload::make('image')
                                                   ->label(__('red-jasmine-article::article.fields.image'))
                                                   ->image(),
                        TextInput::make('description')
                                                  ->label(__('red-jasmine-article::article.fields.description'))
                                                  ->maxLength(255),
                        TagsInput::make('keywords')
                                                  ->label(__('red-jasmine-article::article.fields.keywords'))
                                                  ->nestedRecursiveRules([
                                                      'min:1',
                                                      'max:100',
                                                  ])
                                                  ->reorderable()
                                                  ->separator(' '),

                        ToggleButtons::make('content_type')
                                                      ->label(__('red-jasmine-article::article.fields.content_type'))
                                                      ->required()
                                                      ->inline()
                                                      ->live()
                                                      ->default(ContentTypeEnum::RICH)
                                                      ->useEnum(ContentTypeEnum::class),


                        RichEditor::make('content')
                                                   ->visible(fn(Get $get
                                                   ) : bool => $get('content_type') === ContentTypeEnum::RICH)
                                                   ->required()->label(__('red-jasmine-article::article.fields.content')),
                        MarkdownEditor::make('content')
                                                       ->visible(fn(Get $get
                                                       ) : bool => $get('content_type') === ContentTypeEnum::MARKDOWN)
                                                       ->required()->label(__('red-jasmine-article::article.fields.content')),

                        Textarea::make('content')
                                                 ->visible(fn(Get $get
                                                 ) : bool => $get('content_type') === ContentTypeEnum::TEXT)
                                                 ->required()->label(__('red-jasmine-article::article.fields.content')),


                    ]),
                    Section::make([
                        ...static::ownerFormSchemas(),


                        SelectTree::make('category_id')
                                  ->label(__('red-jasmine-article::article.fields.category_id'))
                                  ->relationship(
                                      relationship: 'category',
                                      titleAttribute: 'name',
                                      parentAttribute: 'parent_id',
                                      modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                                            ->where('owner_id',
                                                                                                                $get('owner_id')),
                                      modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                                                 ->where('owner_id',
                                                                                                                     $get('owner_id'))
                                      ,
                                  )
                                  ->searchable()
                                  ->default(null)
                                  ->enableBranchNode()
                                  ->parentNullValue(0)
                                  ->dehydrateStateUsing(fn($state) => (int) $state),

                        Select::make('tags')
                                               ->multiple()
                                               ->label(__('red-jasmine-article::article.fields.tags'))
                                               ->relationship(
                                                   name: 'tags',
                                                   titleAttribute: 'name',
                                                   modifyQueryUsing: fn(
                                                       $query,
                                                       Get $get,
                                                       ?Model $record
                                                   ) => $query->where('owner_type',
                                                       $get('owner_type'))
                                                              ->where('owner_id',
                                                                  $get('owner_id')),
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
                                               ->label(__('red-jasmine-article::article.fields.is_top'))
                                               ->required()
                                               ->default(false),
                        Toggle::make('is_show')
                                               ->label(__('red-jasmine-article::article.fields.is_show'))
                                               ->required()
                                               ->default(true),
                        TextInput::make('sort')
                                                  ->label(__('red-jasmine-article::article.fields.sort'))
                                                  ->required()
                                                  ->numeric()
                                                  ->default(0)
                                                  ->maxLength(255),
                        ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-article::article.fields.status'))
                                                      ->required()
                                                      ->inline()
                                                      ->disabled()
                                                      ->default(ArticleStatusEnum::DRAFT)
                                                      ->useEnum(ArticleStatusEnum::class),
                        Select::make('approval_status')
                                               ->label(__('red-jasmine-support::support.fields.approval_status'))
                                               ->disabled()
                                               ->useEnum(ApprovalStatusEnum::class),


                    ])->grow(false),
                ])->columnSpanFull(),


                ...static::operateFormSchemas()
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label(__('red-jasmine-article::article.fields.id'))
                                         ->sortable(),
                ...static::ownerTableColumns(),
                TextColumn::make('title')
                                         ->searchable()
                                         ->label(__('red-jasmine-article::article.fields.title')),
                ImageColumn::make('image')
                                          ->label(__('red-jasmine-article::article.fields.image')),
                TextColumn::make('description')
                                         ->label(__('red-jasmine-article::article.fields.description'))
                                         ->searchable(),
                TextColumn::make('keywords')
                                         ->label(__('red-jasmine-article::article.fields.keywords'))
                                         ->searchable(),
                TextColumn::make('category.name')
                                         ->label(__('red-jasmine-article::article.fields.category'))
                                         ->numeric()
                                         ->sortable(),
                IconColumn::make('is_top')
                                         ->label(__('red-jasmine-article::article.fields.is_top'))
                                         ->boolean(),
                TextColumn::make('sort')
                                         ->label(__('red-jasmine-article::article.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                IconColumn::make('is_show')
                                         ->label(__('red-jasmine-article::article.fields.is_show'))
                                         ->boolean(),
                TextColumn::make('status')
                                         ->label(__('red-jasmine-article::article.fields.status'))
                                         ->useEnum(),
                TextColumn::make('approval_status')
                                         ->useEnum()
                                         ->label(__('red-jasmine-article::article.fields.approval_status'))
                ,
                ...static::operateTableColumns()

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
                Action::make('publish')
                                     ->label(__('red-jasmine-article::article.commands.publish'))
                                     ->action(function ($record) {

                                         $command = new ArticlePublishCommand();
                                         $command->setKey($record->getKey());
                                         app(static::$service)->publish($command);

                                     })->visible(fn($record) => $record->canPublish()),
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
            'index'  => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit'   => EditArticle::route('/{record}/edit'),
        ];
    }
}
