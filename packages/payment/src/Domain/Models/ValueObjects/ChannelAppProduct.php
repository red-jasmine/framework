<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Model;

class ChannelAppProduct extends Model
{

    protected $fillable = [
        'system_channel_product_id',
        'system_channel_app_id'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_app_products';
    }

    public function channelApp() : BelongsTo
    {
        return $this->belongsTo(ChannelApp::class, 'system_channel_app_id', 'id');
    }


    public function product() : BelongsTo
    {
        return $this->belongsTo(ChannelProduct::class, 'system_channel_product_id', 'id');
    }

}
