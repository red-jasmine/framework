<?php

namespace RedJasmine\Logistics\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 免费区域表
 */
class LogisticsFreightTemplateFreeRegion extends Model
{
    use SoftDeletes;


    public function template() : BelongsTo
    {
        return $this->belongsTo(LogisticsFreightTemplate::class, 'template_id', 'id');
    }
}
