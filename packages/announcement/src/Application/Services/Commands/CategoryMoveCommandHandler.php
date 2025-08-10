<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use RedJasmine\Announcement\Application\Services\CategoryApplicationService;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CategoryMoveCommandHandler extends CommandHandler
{
    public function __construct(
        protected CategoryApplicationService $service
    ) {
    }

    public function handle(CategoryMoveCommand $command): AnnouncementCategory
    {
        $this->beginDatabaseTransaction();
        
        try {
            $category = $this->service->find($command->getKey());
            
            if (!$category) {
                throw new \Exception('分类不存在');
            }
            
            $category->move($command->parentId, $command->sort);
            $this->service->repository->update($category);
            
            $this->commitDatabaseTransaction();
            return $category;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
