<?php

namespace RedJasmine\Announcement\Infrastructure\Repositories\Eloquent;

use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{
    protected static string $eloquentModelClass = AnnouncementCategory::class;

    public function findByName($name) : ?AnnouncementCategory
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }
}
