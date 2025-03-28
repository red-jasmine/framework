<?php

namespace RedJasmine\Product\Application\Series\Services\Commands;

use Illuminate\Support\Collection;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ProductSeriesCreateCommand extends Data
{


    public UserInterface $owner;

    public string $name;

    public ?string $remarks = null;

    /**
     * @var  Collection<int,ProductSeriesProductData>|null
     */
    public ?Collection $products;


}
