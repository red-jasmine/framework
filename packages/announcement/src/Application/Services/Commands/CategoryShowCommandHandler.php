<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use RedJasmine\Announcement\Application\Services\CategoryApplicationService;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\Application\Commands\CommandHandler;

class CategoryShowCommandHandler extends CommandHandler
{
    public function __construct(
        protected CategoryApplicationService $service
    ) {
    }

    public function handle(CategoryShowCommand $command): AnnouncementCategory
    {
        $this->beginDatabaseTransaction();
        
        try {
            $category = $this->service->find($command->getKey());
            
            if (!$category) {
                throw new \Exception('分类不存在');
            }
            
            $category->show();
            $this->service->repository->update($category);
            
            $this->commitDatabaseTransaction();
            return $category;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
