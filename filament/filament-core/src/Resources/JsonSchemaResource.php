<?php

namespace RedJasmine\FilamentCore\Resources;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Domain\Models\JsonSchema;
use RedJasmine\FilamentCore\Forms\Components\JsonSchemaBuilder;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages\CreateJsonSchema;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages\EditJsonSchema;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages\ListJsonSchemas;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;

class JsonSchemaResource extends Resource
{
    protected static ?string $model = JsonSchema::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'JSON Schema';

    protected static ?string $modelLabel = 'JSON Schema';

    protected static ?string $pluralModelLabel = 'JSON Schemas';

    protected static string|null|\UnitEnum $navigationGroup = '系统';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make([
                        TextInput::make('name')
                            ->label('名称')
                            ->required()
                            ->maxLength(255)
                            ->helperText('JSON Schema 的名称'),

                        TextInput::make('slug')
                            ->label('标识')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL 友好的标识符，用于引用此 Schema'),

                        TextInput::make('title')
                            ->label('标题')
                            ->maxLength(255)
                            ->helperText('JSON Schema 的标题'),

                        Textarea::make('description')
                            ->label('描述')
                            ->rows(3)
                            ->helperText('JSON Schema 的描述信息'),

                        JsonSchemaBuilder::make('schema')
                            ->label('JSON Schema')
                            ->helperText('使用可视化构建器创建 JSON Schema 结构'),

                        Operators::make(),
                    ])->columns(1),
                ])->columnSpanFull(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->copyable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('名称')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('标识')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('title')
                    ->label('标题')
                    ->searchable(),

                TextColumn::make('version')
                    ->label('版本')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJsonSchemas::route('/'),
            'create' => CreateJsonSchema::route('/create'),
            'edit' => EditJsonSchema::route('/{record}/edit'),
        ];
    }
}

