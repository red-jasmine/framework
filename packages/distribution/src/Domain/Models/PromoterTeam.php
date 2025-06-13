<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 分销团队
 */
class PromoterTeam extends Model implements OperatorInterface
{

    public $incrementing = false;


    use HasSnowflakeId;

    use HasOperator;


    public function promoters() : HasMany
    {
        return $this->hasMany(Promoter::class, 'team_id', 'id');
    }
}