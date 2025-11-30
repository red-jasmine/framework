<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin \RedJasmine\Product\Domain\Brand\Models\ProductBrand
 */
class BrandResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        $locale = $request->get('locale', app()->getLocale());

        return [
            'id'          => $this->id,
            'parent_id'   => $this->parent_id,
            'name'        => $this->getTranslatedName($locale),
            'description' => $this->getTranslatedDescription($locale),
            'slogan'      => $this->getTranslatedSlogan($locale),
            'is_show'     => $this->is_show,
            'logo'        => $this->logo,
            'status'      => $this->status,
            'extra'       => $this->extra,
        ];
    }
}
