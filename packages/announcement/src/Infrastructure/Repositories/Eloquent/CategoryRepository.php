<?php

namespace RedJasmine\Announcement\Infrastructure\Repositories\Eloquent;

use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CategoryRepository extends Repository implements CategoryRepositoryInterface
{
    protected static string $modelClass = AnnouncementCategory::class;

    public function findByName($name) : ?AnnouncementCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }
}
