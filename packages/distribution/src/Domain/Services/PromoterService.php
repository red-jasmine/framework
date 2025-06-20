<?php

namespace RedJasmine\Distribution\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterAuditMethodEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Support\Data\System;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Foundation\Service\Service;

class PromoterService extends Service
{

    public function __construct(
        protected PromoterLevelReadRepositoryInterface $levelReadRepository
    ) {
    }


    public function apply(Promoter $promoter, PromoterApplyData $applyData) : Promoter
    {
        // 查询推广员等级
        $promoterLevel = $this->levelReadRepository->findLevel($applyData->level);

        // 创建申请
        $apply = new PromoterApply();

        $apply->level        = $promoterLevel->level;
        $apply->apply_at     = Carbon::now();
        $apply->apply_type   = $applyData->applyType;
        $apply->apply_method = $applyData->applyMethod;
        $apply->audit_method = $promoterLevel->audit_method;
        $apply->setRelation('promoter', $promoter);
        // 添加申请单
        $promoter->apply($apply);
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