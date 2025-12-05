<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

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