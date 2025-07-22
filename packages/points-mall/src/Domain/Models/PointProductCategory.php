<?php

namespace RedJasmine\PointsMall\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OperatorInterface ;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PointProductCategory extends BaseCategoryModel implements OwnerInterface
{

    use HasOwner;
    

    /**
     * 关联商品
     */
    public function products()
    {
        return $this->hasMany(PointsProduct::class, 'category_id');
    }

    /**
     * 获取上架商品数量
     */
    public function getOnSaleProductsCount(): int
    {
        return $this->products()
            ->where('status', 'on_sale')
            ->count();
    }

    /**
     * 获取分类路径
     */
    public function getCategoryPath(): string
    {
        return $this->name;
    }
} 