<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentTrade extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    use SoftDeletes;

    /**
     * Generate unique keys for the model.
     *
     * @return void
     */
    public function setUniqueIds() : void
    {
        parent::setUniqueIds();
        $this->extension->{$this->extension->getKeyName()} = $this->{$this->getKeyName()};
    }


    public static function newModel() : static
    {
        $model = new static();
        $model->setRelation('extension', new PaymentTradeExtension());
        return $model;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(PaymentTradeExtension::class, 'id', 'id');
    }
}
