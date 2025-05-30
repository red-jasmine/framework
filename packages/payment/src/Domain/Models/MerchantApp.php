<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Helpers\Signer\Signer;

class MerchantApp extends Model
{

    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;

    use SoftDeletes;

    use HasOperator;

    protected function casts() : array
    {
        return [
            'status'             => MerchantAppStatusEnum::class,
            'app_public_key'     => 'encrypted',
            'app_private_key'    => 'encrypted',
            'system_public_key'  => 'encrypted',
            'system_private_key' => 'encrypted',
        ];
    }

    public static function boot() : void
    {
        parent::boot();

        static::creating(function (MerchantApp $merchantApp) {
            $merchantApp->generateSystemKeys();
            $merchantApp->generateAppKeys();
        });

    }

    protected $fillable = [
        'merchant_id',
        'name',
        'status'
    ];


    public function getTable() : string
    {
        return 'payment_merchant_apps';
    }


    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }


    /**
     * 生成系统密钥对
     * @return void
     */
    public function generateSystemKeys() : void
    {
        $keys                     = (new Signer())->generateKeys();
        $this->system_public_key  = $keys['public'];
        $this->system_private_key = $keys['private'];
    }


    public function generateAppKeys() : void
    {
        $keys                  = (new Signer())->generateKeys();
        $this->app_public_key  = $keys['public'];
        $this->app_private_key = $keys['private'];
    }

    public function channelApps() : BelongsToMany
    {
        return $this->belongsToMany(
            ChannelApp::class,
            'payment_merchant_channel_app_permissions',
            'merchant_app_id',
            'channel_app_id',
        )->using(MerchantChannelAppPermission::class)
                    ->wherePivot('status', PermissionStatusEnum::ENABLE->value)
                    ->withTimestamps();
    }


}

