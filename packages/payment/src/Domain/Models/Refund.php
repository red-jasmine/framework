<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!$this->exists()) {
            $this->setRelation('extension', new RefundExtension());
        }
    }

    public function setUniqueIds() : void
    {
        parent::setUniqueIds();
        $this->extension->refund_id = $this->{$this->getKeyName()};
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
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_refunds';
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
