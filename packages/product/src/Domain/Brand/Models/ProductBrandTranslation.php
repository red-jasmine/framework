<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryTranslationModel;


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

