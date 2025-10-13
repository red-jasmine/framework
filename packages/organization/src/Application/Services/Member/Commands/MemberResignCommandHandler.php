<?php

namespace RedJasmine\Organization\Application\Services\Member\Commands;

use RedJasmine\Organization\Application\Services\Member\MemberApplicationService;
use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class MemberResignCommandHandler extends CommandHandler
{
    public function __construct(
        protected MemberApplicationService $service
    ) {
    }

    public function handle(MemberResignCommand $command): Member
    {
        $this->beginDatabaseTransaction();

        try {
            $member = $this->service->resign($command->memberId);

            $this->commitDatabaseTransaction();
            return $member;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
