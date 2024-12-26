<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Generator\NotifyNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Notify extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;

    protected $fillable = [
        'merchant_id',
        'merchant_app_id',
        'provider_id',
        'notify_type',
        'business_type',
        'business_no',
        'url',
        'body'
    ];

    public static function boot() : void
    {
        parent::boot();
        static::creating(function (Notify $notify) {
            $notify->generateNo();
        });
    }

    protected function generateNo() : void
    {
        $this->notify_no = app(NotifyNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }

    protected $casts = [
        'status'   => NotifyStatusEnum::class,
        'body'     => 'array',
        'response' => 'array'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_notifies';
    }


    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(MerchantApp::class, 'merchant_app_id', 'id');
    }

}
