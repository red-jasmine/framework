<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RedJasmine\Payment\Domain\Generator\RefundNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\TradeNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Casts\MoneyCast;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\RefundExtension;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property Money $refundAmount
 */
class Refund extends Model
{

    public static function boot() : void
    {
        parent::boot();

        static::creating(static function (Refund $refund) {
            $refund->generateNo();
            if ($refund->relationLoaded('extension')) {
                $refund->extension->refund_id = $refund->id;
            }
        });

    }

    protected function generateNo() : void
    {
        $this->refund_no = app(RefundNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if ($instance->exists === false) {
            $instance->setRelation('extension', RefundExtension::make());
        }

        return $instance;
    }


    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    protected $casts = [
        'status'       => RefundStatusEnum::class,
        'create_time'  => 'datetime',
        'refund_time'  => 'datetime',
        'refundAmount' => MoneyCast::class,
    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_refunds';
    }

    public function trade() : BelongsTo
    {

        return $this->belongsTo(Trade::class, 'trade_id', 'id');
    }


    public function setGoodsDetails(array $goodDetails = []) : void
    {
        $this->extension->good_details = $goodDetails;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(RefundExtension::class, 'refund_id', 'id');
    }


}
