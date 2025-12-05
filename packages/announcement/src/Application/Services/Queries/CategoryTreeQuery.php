<?php

namespace RedJasmine\Announcement\Application\Services\Queries;


use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CategoryTreeQuery extends PaginateQuery
{
    public string        $biz;
    public UserInterface $owner;

}
