<?php

namespace RedJasmine\Address\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Address */
class AddressResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'            => $this->id,
            'owner_type'    => $this->owner_type,
            'owner_id'      => $this->owner_id,
            'contacts'      => $this->contacts,
            'phone'         => $this->phone,
            'country'       => $this->country,
            'province'      => $this->province,
            'city'          => $this->city,
            'district'      => $this->district,
            'street'        => $this->street,
            'company'       => $this->company,
            'address'       => $this->address,
            'more_address'  => $this->more_address,
            'postcode'      => $this->postcode,
            'country_code'  => $this->country_code,
            'province_code' => $this->province_code,
            'city_code'     => $this->city_code,
            'district_code' => $this->district_code,
            'street_code'   => $this->street_code,
            'longitude'     => $this->longitude,
            'latitude'      => $this->latitude,
            'remarks'       => $this->remarks,
            'type'          => $this->type,
            'is_default'    => $this->is_default,
            'sort'          => $this->sort,
            'status'        => $this->status,
            'tag'           => $this->tag,
            'version'       => $this->version,
            'creator_type'  => $this->creator_type,
            'creator_id'    => $this->creator_id,
            'updater_type'  => $this->updater_type,
            'updater_id'    => $this->updater_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
