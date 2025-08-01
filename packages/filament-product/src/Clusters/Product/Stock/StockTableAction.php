<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Stock;

use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
use RedJasmine\Product\Application\Stock\Services\Commands\BulkStockCommand;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
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
                            ->label(__('red-jasmine-product::product.fields.skus'))
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
                                                  Forms\Components\Select::make('status')->disabled()->options(ProductStatusEnum::options()),
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
                  * @var $sku \RedJasmine\Product\Domain\Stock\Models\Product
                  */

                 $record->skus->each(function ($sku) {
                     /**
                      * @var $sku ProductSku
                      */
                     $sku->action_type = ProductStockActionTypeEnum::ADD->value;
                     $sku->makeHidden(['price']);
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
