<?php

namespace RedJasmine\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Logistics\Enums\FreightTemplates\FreightChargeTypeEnum;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\WithDTO;

class LogisticsFreightTemplate extends Model
{
    use SoftDeletes;

    use HasOwner;

    use HasOperator;

    use WithDTO;

    public $incrementing = false;


    protected $casts = [
        'is_free'     => 'bool',
        'charge_type' => FreightChargeTypeEnum::class,
    ];

    public function feeRegions() : HasMany
    {
        return $this->hasMany(LogisticsFreightTemplateFeeRegion::class, 'template_id', 'id');
    }

    public function freeRegions() : HasMany
    {
        return $this->hasMany(LogisticsFreightTemplateFreeRegion::class, 'template_id', 'id');

    }


}
