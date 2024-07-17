<?php

namespace RedJasmine\Card\Application\UserCases\Command\GroupBindProduct;


use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Contracts\UserInterface;

class CardGroupBindProductCreateCommand extends Command
{

    public UserInterface $owner;

    public int $groupId = 0;

    public string $productType;

    public int $productId;

    public int $skuId = 0;


}
