<?php

namespace RedJasmine\Order\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin \RedJasmine\Order\Domain\Models\OrderAddress
 */
class OrderAddressResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'contacts'    => $this->contacts,
            'mobile'      => $this->mobile,
            'country'     => $this->country,
            'province'    => $this->province,
            'city'        => $this->city,
            'district'    => $this->district,
            'street'      => $this->street,
            'country_id'  => $this->country_id,
            'province_id' => $this->province_id,
            'city_id'     => $this->city_id,
            'district_id' => $this->district_id,
            'street_id'   => $this->street_id,
            'address'     => $this->address,
            'zip_code'    => $this->zip_code,
            'lon'         => $this->lon,
            'lat'         => $this->lat,
            'extra'     => $this->extra,
        ];
    }
}
