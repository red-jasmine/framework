<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PointsProductCategory extends BaseCategoryModel implements OwnerInterface
{

    use HasOwner;


    /**
     * 关联商品
     */
    public function products() : HasMany
    {
        return $this->hasMany(PointsProduct::class, 'category_id');
    }

} 