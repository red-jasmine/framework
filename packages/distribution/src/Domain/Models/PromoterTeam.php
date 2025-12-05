<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

/**
 * 分销团队
 */
class PromoterTeam extends BaseCategoryModel
{


    public function promoters() : HasMany
    {
        return $this->hasMany(Promoter::class, 'team_id', 'id');
    }
}