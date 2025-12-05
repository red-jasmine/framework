<?php

namespace RedJasmine\Distribution\Application\PromoterApply\Services\Commands;

use RedJasmine\Distribution\Application\PromoterApply\Services\PromoterApplyApplicationService;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Services\PromoterService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

/**
 * 分销员申请审核指令处理器
 */
class PromoterApplyApprovalCommandHandler extends CommandHandler
{
    public function __construct(
        protected PromoterApplyApplicationService $service,
        protected PromoterService $promoterService,
    ) {
    }

    /**
     * 处理分销员申请审核
     *
     * @param  PromoterApplyApprovalCommand  $command
     *
     * @return PromoterApply
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(PromoterApplyApprovalCommand $command) : PromoterApply
    {
        $this->beginDatabaseTransaction();

        try {

            // 1. 获取申请记录
            $apply = $this->service->repository->find($command->getKey());

            $this->promoterService->approvalApply($apply, $command);

            $this->service->repository->update($apply);

            $this->commitDatabaseTransaction();

            return $apply;

        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}