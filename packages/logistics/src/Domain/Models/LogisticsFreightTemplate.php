<?php

namespace RedJasmine\Logistics\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightChargeTypeEnum;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStatusEnum;
use RedJasmine\Logistics\Domain\Models\Extensions\LogisticsFreightTemplateStrategy;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class LogisticsFreightTemplate extends Model implements OwnerInterface, OperatorInterface
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

   protected $with = ['strategies','strategies.regions'];


    protected function casts() : array
    {
        return [
            'is_free'     => 'bool',
            'charge_type' => FreightChargeTypeEnum::class,
            'status'      => FreightTemplateStatusEnum::class,
        ];
    }


    public function newInstance($attributes = [], $exists = false)
    {
        $instance = parent::newInstance($attributes, $exists); 
        if (!$instance->exists) {
            $instance->id = $this->newUniqueId();
            $instance->setRelation('strategies', Collection::make());
        }
        return $instance;
    }

    public function strategies() : HasMany
    {
        return $this->hasMany(LogisticsFreightTemplateStrategy::class, 'template_id', 'id');
    }


}
