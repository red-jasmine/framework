<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use RedJasmine\Order\Domain\Models\Order;

class OrderExport extends Exporter
{

    protected static ?string $model = Order::class;


    public function getFormats() : array
    {
        return [ ExportFormat::Csv ];
    }
    public function getJobBatchName(): ?string
    {
        return 'order-export';
    }

    public static function getColumns() : array
    {
        return [
            ExportColumn::make('id')->label(__('red-jasmine-order::order.fields.id'))->enabledByDefault(true),
            ExportColumn::make('seller_type')->label(__('red-jasmine-order::order.fields.seller_type')),
            ExportColumn::make('seller_id')->label(__('red-jasmine-order::order.fields.seller_id')),
            ExportColumn::make('seller_nickname')->label(__('red-jasmine-order::order.fields.seller_nickname')),
            ExportColumn::make('buyer_type')->label(__('red-jasmine-order::common.fields.buyer_type')),
            ExportColumn::make('buyer_id')->label(__('red-jasmine-order::common.fields.buyer_id')),
            ExportColumn::make('buyer_nickname')->label(__('red-jasmine-order::common.fields.buyer_nickname')),
            ExportColumn::make('title')->label(__('red-jasmine-order::order.fields.title')),
            ExportColumn::make('order_type')->label(__('red-jasmine-order::order.fields.order_type'))->useEnum(),
            ExportColumn::make('shipping_type')->label(__('red-jasmine-order::order.fields.shipping_type'))->useEnum(),
            ExportColumn::make('order_status')->label(__('red-jasmine-order::order.fields.order_status'))->useEnum(),
            ExportColumn::make('accept_status')->label(__('red-jasmine-order::order.fields.accept_status'))->useEnum(),
            ExportColumn::make('payment_status')->label(__('red-jasmine-order::order.fields.payment_status'))->useEnum(),
            ExportColumn::make('shipping_status')->label(__('red-jasmine-order::order.fields.shipping_status'))->useEnum(),
            ExportColumn::make('rate_status')->label(__('red-jasmine-order::order.fields.rate_status')),
            ExportColumn::make('settlement_status')->label(__('red-jasmine-order::order.fields.settlement_status')),
            ExportColumn::make('seller_custom_status')->label(__('red-jasmine-order::order.fields.seller_custom_status')),
            ExportColumn::make('invoice_status')->label(__('red-jasmine-order::order.fields.invoice_status')),
            ExportColumn::make('product_amount')->label(__('red-jasmine-order::order.fields.product_amount')),
            ExportColumn::make('cost_amount')->label(__('red-jasmine-order::order.fields.cost_amount')),
            ExportColumn::make('tax_amount')->label(__('red-jasmine-order::order.fields.tax_amount')),
            ExportColumn::make('commission_amount')->label(__('red-jasmine-order::order.fields.commission_amount')),
            ExportColumn::make('product_payable_amount')->label(__('red-jasmine-order::order.fields.product_payable_amount')),
            ExportColumn::make('freight_amount')->label(__('red-jasmine-order::order.fields.freight_amount')),
            ExportColumn::make('discount_amount')->label(__('red-jasmine-order::order.fields.discount_amount')),
            ExportColumn::make('payable_amount')->label(__('red-jasmine-order::order.fields.payable_amount')),
            ExportColumn::make('payment_amount')->label(__('red-jasmine-order::order.fields.payment_amount')),
            ExportColumn::make('refund_amount')->label(__('red-jasmine-order::order.fields.refund_amount')),
            ExportColumn::make('created_time')->label(__('red-jasmine-order::order.fields.created_time')),
            ExportColumn::make('payment_time')->label(__('red-jasmine-order::order.fields.payment_time')),
            ExportColumn::make('accept_time')->label(__('red-jasmine-order::order.fields.accept_time')),
            ExportColumn::make('close_time')->label(__('red-jasmine-order::order.fields.close_time')),
            ExportColumn::make('shipping_time')->label(__('red-jasmine-order::order.fields.shipping_time')),
            ExportColumn::make('confirm_time')->label(__('red-jasmine-order::order.fields.confirm_time')),
            ExportColumn::make('refund_time')->label(__('red-jasmine-order::order.fields.refund_time')),
            ExportColumn::make('settlement_time')->label(__('red-jasmine-order::order.fields.settlement_time')),
            ExportColumn::make('channel_type')->label(__('red-jasmine-order::order.fields.channel_type')),
            ExportColumn::make('channel_id')->label(__('red-jasmine-order::order.fields.channel_id')),
            ExportColumn::make('channel_id')->label(__('red-jasmine-order::order.fields.channel_id')),
            ExportColumn::make('channel_name')->label(__('red-jasmine-order::order.fields.channel_name')),
            ExportColumn::make('guide_type')->label(__('red-jasmine-order::order.fields.guide_type')),
            ExportColumn::make('guide_id')->label(__('red-jasmine-order::order.fields.guide_id')),
            ExportColumn::make('guide_name')->label(__('red-jasmine-order::order.fields.guide_name')),
            ExportColumn::make('store_type')->label(__('red-jasmine-order::order.fields.store_type')),
            ExportColumn::make('store_id')->label(__('red-jasmine-order::order.fields.store_id')),
            ExportColumn::make('store_name')->label(__('red-jasmine-order::order.fields.store_name')),
            ExportColumn::make('client_type')->label(__('red-jasmine-order::order.fields.client_type')),
            ExportColumn::make('client_version')->label(__('red-jasmine-order::order.fields.client_version')),
            ExportColumn::make('client_ip')->label(__('red-jasmine-order::order.fields.client_ip')),
            ExportColumn::make('source_type')->label(__('red-jasmine-order::order.fields.source_type')),
            ExportColumn::make('source_id')->label(__('red-jasmine-order::order.fields.source_id')),
            ExportColumn::make('contact')->label(__('red-jasmine-order::order.fields.contact')),
            ExportColumn::make('star')->label(__('red-jasmine-order::order.fields.star')),
            ExportColumn::make('urge')->label(__('red-jasmine-order::common.fields.urge')),
            ExportColumn::make('urge_time')->label(__('red-jasmine-order::common.fields.urge_time')),
            ExportColumn::make('outer_order_id')->label(__('red-jasmine-order::order.fields.outer_order_id')),
            ExportColumn::make('cancel_reason')->label(__('red-jasmine-order::order.fields.cancel_reason')),
            ExportColumn::make('cancel_reason')->label(__('red-jasmine-order::order.fields.cancel_reason')),
            ExportColumn::make('info.seller_remarks')->label(__('red-jasmine-order::order.fields.seller_remarks')),
            ExportColumn::make('info.seller_message')->label(__('red-jasmine-order::order.fields.seller_message')),
            ExportColumn::make('info.buyer_message')->label(__('red-jasmine-order::order.fields.buyer_message')),
            //            ExportColumn::make('info.tools')->label(__('red-jasmine-order::order.fields.tools')),

        ];
    }

    public static function getCompletedNotificationBody(Export $export) : string
    {
        return $export;
    }


}
