<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\BaseCategoryTranslationModel;
use RedJasmine\Support\Domain\Models\OperatorInterface;


class ProductBrandTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{

    /**
     * 关联到品牌
     */
    public function brand() : BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'locale_id');
    }


}

