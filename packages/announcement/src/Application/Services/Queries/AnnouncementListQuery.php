<?php

namespace RedJasmine\Announcement\Application\Services\Queries;


use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class AnnouncementListQuery extends PaginateQuery
{
    public string        $biz;
    public UserInterface $owner;
    public ?int          $categoryId       = null;
    public ?string       $title            = null;
    public ?string       $status           = null;
    public ?string       $approvalStatus   = null;
    public ?bool         $isForceRead      = null;
    public ?string       $publishTimeStart = null;
    public ?string       $publishTimeEnd   = null;
}
