<?php

namespace RedJasmine\Announcement\Application\Services;

use RedJasmine\Announcement\Application\Services\Commands\AnnouncementApproveCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementPublishCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementRejectCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementRevokeCommandHandler;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementSubmitApprovalCommandHandler;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Announcement\Domain\Transformers\AnnouncementTransformer;
use RedJasmine\Support\Application\ApplicationService;

class AnnouncementApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'announcement.application';
    protected static string $modelClass     = Announcement::class;

    public function __construct(
        public AnnouncementRepositoryInterface $repository,
        public AnnouncementTransformer $transformer
    ) {
    }

    protected static $macros = [
        'publish' => AnnouncementPublishCommandHandler::class,
        'revoke'  => AnnouncementRevokeCommandHandler::class
    ];
}
