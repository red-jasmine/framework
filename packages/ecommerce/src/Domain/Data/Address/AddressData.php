<?php

namespace RedJasmine\Ecommerce\Domain\Data\Address;

use RedJasmine\Support\Foundation\Data\Data;

class AddressData extends Data
{
    public ?string $contacts;
    public ?string $phone;
    public ?string $country;
    public ?string $province;
    public ?string $city;
    public ?string $district;
    public ?string $street;
    public ?string $village;
    public ?string $address;
    public ?string $moreAddress;
    public ?string $company;
    public ?string $postcode;
    public ?string $countryCode;
    public ?string $provinceCode;
    public ?string $cityCode;
    public ?string $districtCode;
    public ?string $streetCode;
    public ?string $villageCode;
    public ?string $latitude;
    public ?string $longitude;
    public ?string $tag;
    public ?array  $extra;
}