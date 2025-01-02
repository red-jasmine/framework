<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ChannelApp extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;


    use HasOwner;

    use HasSnowflakeId;


    use SoftDeletes;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channel_apps';
    }


    protected $fillable = [
        'owner_type',
        'owner_id',
        'channel_code',
        'channel_merchant_id',
        'channel_app_id',
        'channel_public_key',
        'channel_app_public_key',
        'channel_app_private_key',
        'status',
    ];

    protected function casts() : array
    {
        return [
            'sign_method'                 => SignMethodEnum::class,
            'status'                      => ChannelAppStatusEnum::class,
            'encrypt_key'                 => 'encrypted',
            'channel_public_key'          => 'encrypted',
            'channel_app_public_key'      => 'encrypted',
            'channel_app_private_key'     => 'encrypted',
            'channel_public_key_cert'     => 'encrypted',
            'channel_root_cert'           => 'encrypted',
            'channel_app_public_key_cert' => 'encrypted',
        ];
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$exists) {
            $instance->setUniqueIds();
            $instance->setRelation('products', Collection::make());
        }
        return $instance;
    }


    public static function boot() : void
    {

        parent::boot();
        static::saving(static function (ChannelApp $channelApp) {


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


    public function channel() : BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_code', 'code');
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(
            ChannelProduct::class,
            config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channel_app_products',
            'payment_channel_app_id',
            'payment_channel_product_id',
        )->withTimestamps();
    }


    /**
     * 是否可用
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === ChannelAppStatusEnum::ENABLE;
    }
}
