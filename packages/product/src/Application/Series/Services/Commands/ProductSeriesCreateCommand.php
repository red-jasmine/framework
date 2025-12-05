<?php

namespace RedJasmine\Product\Application\Series\Services\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class ProductSeriesCreateCommand extends Data
{


    public UserInterface $owner;

    public string $name;

    public ?string $remarks = null;

    /**
     * @var  ProductSeriesProductData[]
     */
    public array $products = [];


}
