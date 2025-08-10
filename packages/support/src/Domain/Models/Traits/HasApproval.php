<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\ApprovalException;

/**
 * 具有审批功能的模型
 * @property ApprovalStatusEnum $approval_status
 * @property ?Carbon $approval_time
 * @property ?string $approval_message
 * @method approvalPass(ApprovalData $data)
 * @method approvalReject(ApprovalData $data)
 * @method approvalRevoke(ApprovalData $data)
 */
trait HasApproval
{


    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasApproval() : void
    {
        // TODO 动态添加转换器


    }

    public function canApproval() : bool
    {
        if ($this->approval_status !== ApprovalStatusEnum::PENDING) {
            return false;
        }
        return true;
    }

    /**
     * 是否提交审批
     * @return bool
     */
    public function canSubmitApproval() : bool
    {
        if ($this->approval_status === ApprovalStatusEnum::PENDING) {
            return false;
        }
        return true;
    }


    /**
     * 提交审批
     * @return void
     * @throws ApprovalException
     */
    public function submitApproval() : void
    {
        if (!$this->canSubmitApproval()) {
            throw new ApprovalException('不支持发起审批');
        }
        $this->approval_status = ApprovalStatusEnum::PENDING;
        $this->approval_time   = Carbon::now();
    }

    /**
     * 处理审批
     *
     * @param  ApprovalData  $data
     *
     * @return void
     * @throws ApprovalException
     */
    public function handleApproval(ApprovalData $data) : void
    {
        if (!$this->canApproval()) {
            throw new ApprovalException();
        }

        $this->approval_status  = $data->status;
        $this->approval_message = $data->message;
        $this->approval_time    = Carbon::now();
        switch ($data->status) {
            case ApprovalStatusEnum::PASS:
                if (method_exists($this, 'approvalPass')) {
                    $this->approvalPass($data);
                }
                $this->fireModelEvent('approvalPass', false);
                break;
            case ApprovalStatusEnum::REJECT:
                if (method_exists($this, 'approvalReject')) {
                    $this->approvalReject($data);
                }
                $this->fireModelEvent('approvalReject', false);
                break;
            case ApprovalStatusEnum::REVOKE:
                if (method_exists($this, 'approvalRevoke')) {
                    $this->approvalRevoke($data);
                }
                $this->fireModelEvent('approvalRevoke', false);
                break;
            default:
                break;
        }
    }
}