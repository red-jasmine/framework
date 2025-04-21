<?php

namespace RedJasmine\Address\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class AddressData extends Data
{
    /**
     * 所属人
     * @var UserInterface
     */
    public UserInterface $owner;


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

    public ?string $type;
    public ?string $tag;
    public ?string $remarks;
    public int     $sort      = 1;
    public bool    $isDefault = false;


}