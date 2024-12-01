<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Models\PaymentChannelProduct;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentChannelAppProduct extends Model
{

    use HasOperator;


    protected $fillable = [
        'payment_channel_product_id',
        'payment_channel_app_id'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_app_products';
    }

    public function channelApp() : BelongsTo
    {
        return $this->belongsTo(PaymentChannelApp::class, 'payment_channel_app_id', 'id');
    }


    public function product() : BelongsTo
    {
        return $this->belongsTo(PaymentChannelProduct::class, 'payment_channel_product_id', 'id');
    }

}
