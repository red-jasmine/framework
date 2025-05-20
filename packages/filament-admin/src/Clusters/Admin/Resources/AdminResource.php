<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Models\Enums\AdminGenderEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminStatusEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminTypeEnum;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\RelationManagers;
use RedJasmine\FilamentAdmin\Clusters\AdminCluster as AdminClusters;
use RedJasmine\FilamentCore\Helpers\PageHelper;

class AdminResource extends Resource
{


    public static $translationNamespace = 'red-jasmine-admin::admin';

    use PageHelper;

    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $cluster = AdminClusters::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-admin::admin.labels.title');
    }

    public static function form(Form $form) : Form
    {
        $form
            ->schema(components: [
                Forms\Components\ToggleButtons::make('type')
                                              ->required()
                                              ->inline()
                                              ->useEnum(AdminTypeEnum::class)
                                              ->default(AdminTypeEnum::ADMIN),
                Forms\Components\ToggleButtons::make('status')
                                              ->required()
                                              ->inline()
                                              ->useEnum(AdminStatusEnum::class)
                                              ->default(AdminStatusEnum::ACTIVATED),
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('phone')
                                          ->tel()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('email')
                                          ->email()
                                          ->maxLength(255),

                Forms\Components\Select::make('roles')
                                       ->relationship('roles', 'name')
                                       ->multiple()
                                       ->preload()
                                       ->searchable()
                ,
                Forms\Components\TextInput::make('password')
                                          ->password()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('nickname')
                                          ->maxLength(64),
                Forms\Components\ToggleButtons::make('gender')
                                              ->inline()
                                              ->useEnum(AdminGenderEnum::class)
                ,
                Forms\Components\FileUpload::make('avatar')
                                           ->image(),
                Forms\Components\DatePicker::make('birthday')
                ,
                Forms\Components\TextInput::make('biography')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('country')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('province')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('city')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('district')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('school')
                                          ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at')
                                               ->visibleOn('view')
                ,
                Forms\Components\DateTimePicker::make('last_active_at')
                                               ->visibleOn('view')
                ,
                Forms\Components\TextInput::make('ip')
                                          ->visibleOn('view'),
                Forms\Components\DateTimePicker::make('cancel_time')
                                               ->visibleOn('view')
                ,
                ...static::operateFormSchemas()
            ]);
        static::translationLabels($form);
        return $form;
    }

    public static function table(Table $table) : Table
    {
        $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('type')
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('status')
                                         ->useEnum()
                ,
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('email')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('nickname')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('avatar')
                ,
                Tables\Columns\TextColumn::make('birthday')
                                         ->date()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('biography')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('province')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('district')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('school')
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                                         ->dateTime()
                                         ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('last_active_at')
                                         ->dateTime()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('ip')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('cancel_time')
                                         ->dateTime()
                                         ->toggleable(isToggledHiddenByDefault: true),
                ...static::operateTableColumns(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);

        static::translationLabels($table);
        return $table;
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
            'index'  => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit'   => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
