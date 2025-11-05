<?php

namespace RedJasmine\Region\UI\Http\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin Country
 */
class CountryResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'code'         => $this->code,
            'iso_alpha_3'  => $this->iso_alpha_3,
            'name'         => $this->name,
            'native'       => $this->native,
            'region'       => $this->region,
            'currency'     => $this->currency,
            'phone_code'   => $this->phone_code,
            'longitude'    => $this->longitude,
            'latitude'     => $this->latitude,
            'timezones'    => $this->timezones,
            'translations' => $this->translations,
        ];

    }

}
