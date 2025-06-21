<?php

namespace RedJasmine\Distribution\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Contracts\PromoterConditionInterface;
use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Facades\PromoterConditionFacade;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyAuditStatusEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterAuditMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Distribution\Exceptions\PromoterApplyException;
use RedJasmine\Support\Data\System;
use RedJasmine\Support\Foundation\Service\Service;

class PromoterService extends Service
{

    public function __construct(
        protected PromoterLevelReadRepositoryInterface $levelReadRepository
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
        if ($applyData->applyType === PromoterApplyTypeEnum::REGISTER
            && $promoter->status === PromoterStatusEnum::APPLYING
        ) {
            return false;
        }

        // 如果 在审批中的申请 那么也不支持
        if ($promoter->exists() && $promoter->applies()->pending()->count() > 0) {
            return false;
        }


        return true;
    }

    /**
     * @param  Promoter  $promoter
     * @param  PromoterApplyData  $applyData
     *
     * @return Promoter
     * @throws PromoterApplyException
     */
    public function apply(Promoter $promoter, PromoterApplyData $applyData) : Promoter
    {


        if ($this->isAllowApply($promoter, $applyData) === false) {
            throw new PromoterApplyException('不支持申请');
        }

        // 查询推广员等级
        $promoterLevel = $this->levelReadRepository->findLevel($applyData->level);

        // 查询是否符合资格
        $isMeetConditions = $this->isMeetConditions($promoterLevel, $promoter);

        if ($isMeetConditions === false) {
            throw new PromoterApplyException('不满足申请条件');
        }


        // 创建申请
        $apply               = new PromoterApply();
        $apply->level        = $promoterLevel->level;
        $apply->apply_at     = Carbon::now();
        $apply->apply_type   = $applyData->applyType;
        $apply->apply_method = $applyData->applyMethod;
        $apply->audit_method = $promoterLevel->audit_method;
        $apply->audit_status = PromoterApplyAuditStatusEnum::PENDING;

        $apply->setRelation('promoter', $promoter);
        // 添加申请单
        $promoter->apply($apply);

        // 自动通过
        if ($apply->audit_method === PromoterAuditMethodEnum::AUTO) {
            $system = System::make();
            $apply->approve($system, null);
        }

        return $promoter;

    }

    public function approve(Promoter $promoter, PromoterApply $apply, string $reason = null)
    {

        $apply->approve();

    }
}