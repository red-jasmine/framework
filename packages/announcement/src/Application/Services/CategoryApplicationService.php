<?php

namespace RedJasmine\Announcement\Application\Services;

use RedJasmine\Announcement\Application\Services\Commands\CategoryHideCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\CategoryMoveCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\CategoryShowCommandHandler;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Announcement\Domain\Transformers\CategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

class CategoryApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'announcement.category.application';
    protected static string $modelClass     = AnnouncementCategory::class;

    public function __construct(
        public CategoryRepositoryInterface $repository,
        public CategoryTransformer $transformer
    ) {
    }

    public function tree(Query $query) : array
    {
        return $this->repository->tree($query);
    }

    protected static $macros = [
        'show' => CategoryShowCommandHandler::class,
        'hide' => CategoryHideCommandHandler::class,
        'move' => CategoryMoveCommandHandler::class,
    ];
}
