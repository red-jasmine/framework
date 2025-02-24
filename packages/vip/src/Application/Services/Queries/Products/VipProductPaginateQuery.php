<?php

namespace RedJasmine\Vip\Application\Services\Queries\Products;

class VipProductPaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{

    public ?string $status;


    public string $appId;

    public string $type;

}