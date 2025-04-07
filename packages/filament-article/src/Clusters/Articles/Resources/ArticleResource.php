<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Models\Enums\ArticleContentTypeEnum;
use RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;

class ArticleResource extends Resource
{

    use ResourcePageHelper;

    /**
     * @var class-string<ProductApplicationService::class>
     */
    protected static ?string $service       = ArticleApplicationService::class;
    protected static ?string $createCommand = ArticleData::class;
    protected static ?string $updateCommand = ArticleData::class;
    protected static bool    $onlyOwner     = true;


    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-c-document-text';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article.labels.article');
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['extension'];
        return $findQuery;
    }


    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                Forms\Components\Split::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('title')
                                                  ->label(__('red-jasmine-article::article.fields.title'))
                                                  ->required()
                                                  ->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                                                   ->label(__('red-jasmine-article::article.fields.image'))
                                                   ->image(),
                        Forms\Components\TextInput::make('description')
                                                  ->label(__('red-jasmine-article::article.fields.description'))
                                                  ->maxLength(255),
                        Forms\Components\TagsInput::make('keywords')
                                                  ->label(__('red-jasmine-article::article.fields.keywords'))
                                                  ->nestedRecursiveRules([
                                                      'min:1',
                                                      'max:100',
                                                  ])
                                                  ->reorderable()
                                                  ->separator(' '),

                        Forms\Components\ToggleButtons::make('content_type')
                                                      ->label(__('red-jasmine-article::article.fields.content_type'))
                                                      ->required()
                                                      ->inline()
                                                      ->live()
                                                      ->default(ArticleContentTypeEnum::RICH->value)
                                                      ->useEnum(ArticleContentTypeEnum::class),


                        Forms\Components\RichEditor::make('content')
                                                   ->visible(fn(Forms\Get $get
                                                   ) : bool => $get('content_type') === ArticleContentTypeEnum::RICH->value)
                                                   ->required()->label(__('red-jasmine-article::article.fields.content')),
                        Forms\Components\Textarea::make('content')
                                                 ->visible(fn(Forms\Get $get
                                                 ) : bool => $get('content_type') === ArticleContentTypeEnum::TEXT->value)
                                                 ->required()->label(__('red-jasmine-article::article.fields.content')),
                        Forms\Components\MarkdownEditor::make('content')
                                                       ->visible(fn(Forms\Get $get
                                                       ) : bool => $get('content_type') === ArticleContentTypeEnum::MARKDOWN->value)
                                                       ->required()->label(__('red-jasmine-article::article.fields.content')),


                    ]),
                    Forms\Components\Section::make([
                        ...static::ownerFormSchemas(),

                        Forms\Components\Select::make('category_id')
                                               ->label(__('red-jasmine-article::article.fields.category_id'))
                                               ->relationship('category', 'name'),
                        Forms\Components\Toggle::make('is_top')
                                               ->label(__('red-jasmine-article::article.fields.is_top'))
                                               ->required()
                                               ->default(false),
                        Forms\Components\Toggle::make('is_show')
                                               ->label(__('red-jasmine-article::article.fields.is_show'))
                                               ->required()
                                               ->default(false),
                        Forms\Components\TextInput::make('sort')
                                                  ->label(__('red-jasmine-article::article.fields.sort'))
                                                  ->required()
                                                  ->numeric()
                                                  ->default(0)
                                                  ->maxLength(255),
                        Forms\Components\ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-article::article.fields.status'))
                                                      ->required()
                                                      ->inline()
                                                      ->disabled()
                                                      ->default(ArticleStatusEnum::DRAFT)
                                                      ->useEnum(ArticleStatusEnum::class),
                        Forms\Components\Select::make('approval_status')
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
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-article::article.fields.id'))
                                         ->sortable(),
                ...static::ownerTableColumns(),
                Tables\Columns\TextColumn::make('title')
                                         ->searchable()
                                         ->label(__('red-jasmine-article::article.fields.title')),
                Tables\Columns\ImageColumn::make('image')
                                          ->label(__('red-jasmine-article::article.fields.image')),
                Tables\Columns\TextColumn::make('description')
                                         ->label(__('red-jasmine-article::article.fields.description'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('keywords')
                                         ->label(__('red-jasmine-article::article.fields.keywords'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                                         ->label(__('red-jasmine-article::article.fields.category'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_top')
                                         ->label(__('red-jasmine-article::article.fields.is_top'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-article::article.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-article::article.fields.is_show'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-article::article.fields.status'))
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('approval_status')
                                         ->label(__('red-jasmine-article::article.fields.approval_status'))
                                         ->searchable(),
                ...static::operateTableColumns()

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
            'index'  => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit'   => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
