<?php

namespace RedJasmine\Address\Application\UserCases\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class AddressCreateCommand extends Data
{

    /**
     * 所属人
     * @var UserInterface
     */
    public UserInterface $owner;

    /**
     * 类型
     * @var string
     */
    public string $type;


    public string  $contacts;
    public string  $mobile;
    public ?string $address;
    public ?string $zipCode;
    public ?string $remarks;
    public ?string $tag;
    public bool    $isDefault = false;
    public int     $sort      = 0;

}
