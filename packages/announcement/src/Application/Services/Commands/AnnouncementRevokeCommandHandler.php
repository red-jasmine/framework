<?php

namespace RedJasmine\Announcement\Application\Services\Commands;

use Exception;
use RedJasmine\Announcement\Application\Services\AnnouncementApplicationService;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class AnnouncementRevokeCommandHandler extends CommandHandler
{
    public function __construct(
        protected AnnouncementApplicationService $service
    ) {
    }

    public function handle(AnnouncementRevokeCommand $command) : Announcement
    {
        $this->beginDatabaseTransaction();

        try {

            $announcement = $this->service->repository->find($command->getKey());

            if (!$announcement) {
                throw new Exception('公告不存在');
            }

            $announcement->revoke();
            $this->service->repository->update($announcement);

            $this->commitDatabaseTransaction();
            return $announcement;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
