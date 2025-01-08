<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RedJasmine\Payment\Domain\Data\TransferPayee;
use RedJasmine\Payment\Domain\Events\Transfers\TransferCreatedEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferSuccessEvent;
use RedJasmine\Payment\Domain\Generator\TransferNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Casts\MoneyCast;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\TransferExtension;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Transfer extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public static function boot() : void
    {
        parent::boot();
        static::creating(function (Transfer $transfer) {
            $transfer->generateNo();
            if ($transfer->relationLoaded('extension')) {
                $transfer->extension->transfer_id = $transfer->id;
            }
        });
    }

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_transfers';
    }

    protected function casts() : array
    {
        return [
            'transfer_status' => TransferStatusEnum::class,
            'scene_code'      => TransferSceneEnum::class,
            'amount'          => MoneyCast::class,
        ];
    }

    protected $dispatchesEvents = [
        'created' => TransferCreatedEvent::class,
        'success' => TransferSuccessEvent::class,
    ];

    protected function generateNo() : void
    {
        $this->transfer_no = app(TransferNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }


    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $this->setUniqueIds();
            $instance->setRelation('extension', new TransferExtension());
        }
        return $instance;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(TransferExtension::class, 'transfer_id', 'id');
    }


    /**
     *
     * @return Attribute
     */
    public function payee() : Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                return TransferPayee::from([
                                               'identityType' => $attributes['payee_identity_type'],
                                               'identityId'   => $attributes['payee_identity_id'],
                                               'name'         => $attributes['payee_name'],
                                               'certType'     => $attributes['payee_cert_type'],
                                               'certNo'       => $attributes['payee_cert_no'],
                                           ]);
            },
            set: static function (TransferPayee $payee) {
                $attributes                        = [];
                $attributes['payee_identity_type'] = $payee->identityType->value;
                $attributes['payee_identity_id']   = $payee->identityId;
                $attributes['payee_name']          = $payee->name;
                $attributes['payee_cert_type']     = $payee->certType?->value;
                $attributes['payee_cert_no']       = $payee->certNo;
                return $attributes;
            },

        );
    }

}
