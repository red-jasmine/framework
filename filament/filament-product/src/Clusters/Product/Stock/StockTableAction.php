<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Stock;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommand;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Support\Exceptions\AbstractException;

class StockTableAction extends Action
{

    protected function setUp() : void
    {
        parent::setUp();

        $this->label(__('red-jasmine-product::product-stock.labels.edit'))
             ->modalWidth('7xl')
             ->slideOver()
             ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
             ->stickyModalFooter()
             ->schema([

                 TextInput::make('id')
                          ->label(__('red-jasmine-product::product.fields.id'))
                          ->readOnly(),
                 TextInput::make('title')
                          ->label(__('red-jasmine-product::product.fields.title'))
                          ->readOnly(),
                 TextInput::make('outer_id')
                          ->label(__('red-jasmine-product::product.fields.outer_id'))
                          ->readOnly(),
                 FileUpload::make('image')->image()->disabled()
                           ->label(__('red-jasmine-product::product.fields.image'))
                 ,
                 Forms\Components\Repeater::make('variants')
                                          ->label(__('red-jasmine-product::product.fields.variants'))
                                          ->table([
                                              Forms\Components\Repeater\TableColumn::make('id'),
                                              Forms\Components\Repeater\TableColumn::make('properties_name'),
                                              Forms\Components\Repeater\TableColumn::make('barcode'),
                                              Forms\Components\Repeater\TableColumn::make('outer_id'),
                                              Forms\Components\Repeater\TableColumn::make('status'),
                                              Forms\Components\Repeater\TableColumn::make('stock'),
                                              Forms\Components\Repeater\TableColumn::make('lock_stock'),
                                              Forms\Components\Repeater\TableColumn::make('action_type'),
                                              Forms\Components\Repeater\TableColumn::make('action_stock'),

                                          ])
                                          ->schema([
                                              Hidden::make('properties_sequence'),
                                              TextInput::make('id')->readOnly(),
                                              TextInput::make('properties_name')->readOnly(),
                                              TextInput::make('barcode')->readOnly(),
                                              TextInput::make('outer_id')->readOnly(),
                                              Select::make('status')->disabled()->options(ProductStatusEnum::options()),
                                              TextInput::make('stock')->readOnly(),
                                              TextInput::make('safety_stock')->readOnly(),
                                              Select::make('action_type')->required()
                                                    ->default(ProductStockActionTypeEnum::ADD->value)
                                                    ->options(ProductStockActionTypeEnum::allowActionTypes()),
                                              TextInput::make('action_stock')->numeric()->default(null),

                                          ])
                                          ->inlineLabel(false)
                                          ->columnSpan('full')

                                          ->reorderable(false)
                                          ->addable(false)
                                          ->deletable(false)
             ])
             ->fillForm(function ($record) : array {
                 /**
                  * @var $sku \RedJasmine\Product\Domain\Stock\Models\Product
                  */

                 $record->variants->each(function ($sku) {
                     /**
                      * @var $sku ProductVariant
                      */
                     $sku->action_type = ProductStockActionTypeEnum::ADD->value;
                     $sku->makeHidden(['price']);
                 });


                 return [
                     'id'       => $record->id,
                     'title'    => $record->title,
                     'outer_id' => $record->outer_id,
                     'image'    => $record->image,
                     'variants'     => $record->variants
                 ];
             })
             ->action(function (array $data) {

                 try {
                     foreach ($data['variants'] ?? [] as $index => $sku) {
                         $data['variants'][$index]['sku_id'] = $sku['id'];
                     }
                     $service = app(StockApplicationService::class);

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

}
