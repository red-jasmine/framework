<?php

namespace RedJasmine\Card\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class CardGroupBindProductData extends Data
{

    public UserInterface $owner;

    public int $groupId = 0;

    public string $productType;

    public int $productId;

    public int $skuId = 0;

}
