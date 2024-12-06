<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentChannelApp extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;


    use HasOwner;

    use HasSnowflakeId;


    use SoftDeletes;

    use HasOperator;


    protected $fillable = [
        'channel_code',
        'channel_merchant_id',
        'channel_app_id',
        'channel_public_key',
        'channel_app_public_key',
        'channel_app_private_key',
        'status',
    ];

    protected $casts = [
        'status'                  => ChannelAppStatusEnum::class,
        'channel_public_key'      => AesEncrypted::class,
        'channel_app_public_key'  => AesEncrypted::class,
        'channel_app_private_key' => AesEncrypted::class,
    ];


    public static function newModel() : static
    {

        $model = new static();

        $model->id = $model->newUniqueId();

        $model->setRelation('products', Collection::make());

        return $model;

    }


    public static function boot() : void
    {

        parent::boot();
        static::saving(static function (PaymentChannelApp $channelApp) {


            if ($channelApp->relationLoaded('products')) {
             
                if ($channelApp->products?->count() > 0) {
                    if (!is_array($channelApp->products->first())) {
                        $data = $channelApp->products;
                    } else {
                        $data = $channelApp->products = $channelApp->products->pluck('id')->toArray();
                    }
                    $channelApp->products()->sync($data);
                    $channelApp->load('products');

                } else {

                    $channelApp->products()->sync([]);
                }
            }
        });
    }


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps';
    }


    public function channel() : BelongsTo
    {
        return $this->belongsTo(PaymentChannel::class, 'channel', 'code');
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(
            PaymentChannelProduct::class,
            config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_app_products',
            'payment_channel_app_id',
            'payment_channel_product_id',
        )->withTimestamps();
    }


}
