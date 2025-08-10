<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function findByName($name) : ?AnnouncementCategory;
}
