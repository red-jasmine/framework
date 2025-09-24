<?php

namespace RedJasmine\Distribution\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Contracts\PromoterConditionInterface;
use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Facades\PromoterConditionFacade;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApprovalMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Distribution\Exceptions\PromoterApplyException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\System;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\ApprovalException;
use RedJasmine\Support\Foundation\Service\Service;


class PromoterService extends Service
{

    public function __construct(
        protected PromoterLevelRepositoryInterface $levelRepository
    ) {
    }


    protected function isMeetConditions(PromoterLevel $promoterLevel, Promoter $promoter) : bool
    {

        $isMeet = true;
        // 查询 分销员 条件值
        foreach ($promoterLevel->upgrades as $conditionConfig) {

            /**
             * @var ConditionData $conditionConfig
             */
            if ($conditionConfig->enabled === false) {
                continue;
            }

            /**
             * @var PromoterConditionInterface $condition
             */

            $condition = PromoterConditionFacade::create($conditionConfig->name);

            $conditionMeet = $condition::isMeet($promoter, $conditionConfig);
            if ($conditionMeet === false) {
                $isMeet = false;
            }

        }
        return $isMeet;
    }

    protected function isAllowApply(Promoter $promoter, PromoterApplyData $applyData) : bool
    {

        // 如果状态在 申请中 那么就不支持申请
        if ($applyData->applyType === PromoterApplyTypeEnum::REGISTER) {

            if ($promoter->status === PromoterStatusEnum::APPLYING) {
                return false;
            }

            // 如果已经是第一级别
            if ((int) ($promoter->level) !== 0) {

                return false;
            }

        }

        if ($applyData->applyType === PromoterApplyTypeEnum::UPGRADE) {

            if ($applyData->level <= $promoter->level) {
                return false;
            }

        }

        // 如果 在审批中的申请 那么也不支持
        if ($promoter->exists() && $promoter->applies()->pending()->count() > 0) {
            return false;
        }


        return true;
    }

    /**
     * 申请
     *
     * @param  Promoter  $promoter
     * @param  PromoterApplyData  $applyData
     *
     * @return Promoter
     * @throws PromoterApplyException|ApprovalException
     */
    public function apply(Promoter $promoter, PromoterApplyData $applyData) : Promoter
    {


        if ($this->isAllowApply($promoter, $applyData) === false) {
            throw new PromoterApplyException('不支持申请');
        }

        // 查询推广员等级
        $promoterLevel = $this->levelRepository->findLevel($applyData->level);


        // 查询是否符合资格
        $isMeetConditions = $this->isMeetConditions($promoterLevel, $promoter);

        if ($isMeetConditions === false) {
            throw new PromoterApplyException('不满足申请条件');
        }


        // 创建申请
        $apply                  = new PromoterApply();
        $apply->level           = $promoterLevel->level;
        $apply->apply_at        = Carbon::now();
        $apply->apply_type      = $applyData->applyType;
        $apply->apply_method    = $applyData->applyMethod;
        $apply->approval_method = $promoterLevel->approval_method;


        // 添加申请单
        $promoter->apply($apply);

        // 提交审批
        $apply->submitApproval();


        // 自动通过

        if ($apply->approval_method === PromoterApprovalMethodEnum::AUTO) {
            $ApprovalData = ApprovalData::from([
                'approver'       => System::make(),
                'status' => ApprovalStatusEnum::PASS,
            ]);
            $apply->handleApproval($ApprovalData);
        }

        return $promoter;

    }

    /**
     * 审核分销员申请
     *
     * @param  PromoterApply  $apply  申请记录
     * @param  ApprovalData  $approvalData  审批数据
     *
     * @throws ApprovalException
     */
    public function approvalApply(PromoterApply $apply, ApprovalData $approvalData) : void
    {
        // 执行标准审批流程
        $apply->handleApproval($approvalData);
    }


}