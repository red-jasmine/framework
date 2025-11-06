<?php

namespace RedJasmine\Region\UI\Http\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 国家资源
 */
class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 如果是数组，直接返回
        if (is_array($this->resource)) {
            return $this->resource;
        }

        // 如果是对象，按属性访问
        return [
            'code'         => $this->code ?? null,
            'iso_alpha_3'  => $this->iso_alpha_3 ?? null,
            'name'         => $this->name ?? null,
            'native'       => $this->native ?? null,
        ];
    }
}
