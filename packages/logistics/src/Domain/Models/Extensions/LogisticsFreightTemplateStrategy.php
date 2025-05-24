<?php

namespace RedJasmine\Logistics\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStrategyTypeEnum;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 收费区域表
 */
class LogisticsFreightTemplateStrategy extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use SoftDeletes;


    protected static function boot()
    {
        parent::boot();


        static::saving(callback: function ($model) {
            if ($model->relationLoaded('regions')) {
                $relations = $model->getRelation('regions');
                if ($relations?->count() > 0) {
                    if (!is_array($relations->first())) {
                        $model->regions()->sync($relations);
                    } else {
                        $model->regions()->sync($relations->pluck('code')->toArray());
                    }
                } else {
                    $model->regions()->sync([]);
                }
                $model->load('regions');
            }
        });
    }


    protected function casts() : array
    {
        return [
            'type'           => FreightTemplateStrategyTypeEnum::class,
            'regions'        => 'array',
            'is_all_regions' => 'boolean',
            'standard_fee'   => MoneyCast::class,
            'extra_fee'      => MoneyCast::class,
        ];
    }

    protected $appends = ['standard_fee', 'extra_fee'];

    public function newInstance($attributes = [], $exists = false)
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $instance->id = $instance->newUniqueId();
            $instance->setRelation('regions', Collection::make([]));

        }
        return $instance;
    }

    public function regions() : BelongsToMany
    {
        return $this->belongsToMany(
            Region::class,
            'logistics_freight_template_strategy_regions',
            'strategy_id',
            'code',
        )->withTimestamps();
    }


    public function template() : BelongsTo
    {
        return $this->belongsTo(LogisticsFreightTemplate::class, 'template_id', 'id');
    }
}
