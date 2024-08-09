<?php

namespace RedJasmine\Shopping\Application\UserCases\Commands;

use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Data\UserData;

/**
 * 商品立即购买
 */
class ProductBuyCommand extends Command
{

    /**
     * 买家
     * @var UserData
     */
    public UserData $buyer;


    public ?string $address;


    public int $productId;


    public int $skuId;


    public int $quantity = 1;








}
