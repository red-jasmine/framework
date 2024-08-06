<?php

namespace RedJasmine\Shopping\Application\UserCases\Commands;

use Illuminate\Support\Collection;
use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Data\UserData;

class ProductBuyCommand extends Command
{

    /**
     * 买家
     * @var UserData
     */
    public UserData $buyer;



    public ?string $address;


    public Collection $products;




}
