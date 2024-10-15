<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductStockResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockResource\RelationManagers;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\BulkStockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Support\Exceptions\AbstractException;

class ProductStockResource extends Resource
{
    protected static ?string $model = \RedJasmine\Product\Domain\Stock\Models\Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 1;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-stock.labels.product-stock');
    }


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-stock.labels.product-stock');
    }

    public static function table(Table $table) : Table
    {

        return $table
            ->striped()
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-product::product-stock.fields.id'))
                                                   ->copyable()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->label(__('red-jasmine-product::product-stock.fields.owner_type'))
                          ,
                          Tables\Columns\TextColumn::make('owner_id')->label(__('red-jasmine-product::product-stock.fields.owner_id')),
                          Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-product::product.fields.title')),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image'))->size(40),
                          Tables\Columns\TextColumn::make('outer_id')->label(__('red-jasmine-product::product.fields.outer_id')),

                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::product.fields.status'))->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('stock')->label(__('red-jasmine-product::product-stock.fields.stock')),
                          Tables\Columns\TextColumn::make('lock_stock')->label(__('red-jasmine-product::product-stock.fields.lock_stock')),

                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          static::editStockAction()
                      ])
            ->recordUrl(null)
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }

    protected static function editStockAction() : Action
    {
        return Action::make('edit')
                     ->label(__('red-jasmine-product::product-stock.labels.edit'))
                     ->modalWidth('7xl')
                     ->slideOver()
                     ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                     ->stickyModalFooter()
                     ->form([

                                Forms\Components\TextInput::make('id')
                                                          ->label(__('red-jasmine-product::product.fields.id'))
                                                          ->readOnly(),
                                Forms\Components\TextInput::make('title')
                                                          ->label(__('red-jasmine-product::product.fields.title'))
                                                          ->readOnly(),
                                Forms\Components\TextInput::make('outer_id')
                                                          ->label(__('red-jasmine-product::product.fields.outer_id'))
                                                          ->readOnly(),
                                Forms\Components\FileUpload::make('image')->image()->disabled()
                                                           ->label(__('red-jasmine-product::product.fields.image'))
                                ,
                                TableRepeater::make('skus')
                                             ->headers([
                                                           Header::make('SKU ID')->label(__('red-jasmine-product::product.fields.sku_id')),
                                                           Header::make('properties_name')->label(__('red-jasmine-product::product.fields.properties_name')),
                                                           Header::make('barcode')->label(__('red-jasmine-product::product.fields.barcode')),
                                                           Header::make('outer_id')->label(__('red-jasmine-product::product.fields.outer_id')),
                                                           Header::make('status')->label(__('red-jasmine-product::product.fields.status')),
                                                           Header::make('stock')->label(__('red-jasmine-product::product.fields.stock')),
                                                           Header::make('lock_stock')->label(__('red-jasmine-product::product.fields.lock_stock')),
                                                           Header::make('action_type')->label(__('red-jasmine-product::product-stock-log.fields.action_type')),
                                                           Header::make('action_stock')->label(__('red-jasmine-product::product-stock-log.fields.action_stock')),

                                                       ])
                                             ->schema([
                                                          Forms\Components\Hidden::make('properties_sequence'),
                                                          Forms\Components\TextInput::make('id')->readOnly(),
                                                          Forms\Components\TextInput::make('properties_name')->readOnly(),
                                                          Forms\Components\TextInput::make('barcode')->readOnly(),
                                                          Forms\Components\TextInput::make('outer_id')->readOnly(),
                                                          Forms\Components\TextInput::make('status')->readOnly(),
                                                          Forms\Components\TextInput::make('stock')->readOnly(),
                                                          Forms\Components\TextInput::make('safety_stock')->readOnly(),
                                                          Forms\Components\Select::make('action_type')->required()
                                                                                 ->default(ProductStockActionTypeEnum::ADD->value)
                                                                                 ->options(ProductStockActionTypeEnum::allowActionTypes()),
                                                          Forms\Components\TextInput::make('action_stock')->numeric()->default(null),

                                                      ])
                                             ->inlineLabel(false)
                                             ->columnSpan('full')
                                             ->streamlined()
                                             ->reorderable(false)
                                             ->addable(false)
                                             ->deletable(false)
                            ])
                     ->fillForm(function ($record) : array {
                         /**
                          * @var $sku Model
                          */

                         $record->skus->each(function ($sku) {
                             $sku->action_type = ProductStockActionTypeEnum::ADD;

                         });

                         return [
                             'id'       => $record->id,
                             'title'    => $record->title,
                             'outer_id' => $record->outer_id,
                             'image'    => $record->image,
                             'skus'     => $record->skus
                         ];
                     })
                     ->action(function (array $data) {

                         try {
                             foreach ($data['skus'] ?? [] as $index => $sku) {
                                 $data['skus'][$index]['sku_id'] = $sku['id'];
                             }
                             $service = app(StockCommandService::class);

                             $service->bulk(BulkStockCommand::from($data));


                             Notification::make()->title('成功')->success()->send();
                         } catch (AbstractException $throwable) {
//                             throw $throwable;
                             Notification::make()->title('失败')
                                         ->body($throwable->getMessage())
                                         ->warning()->send();
                         }


                     });

    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         //
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
            'index' => Product\Resources\ProductStockResource\Pages\ListProductStocks::route('/'),
        ];
    }
}
