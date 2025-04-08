<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\ApprovalException;

/**
 * @property ApprovalStatusEnum $approval_status
 * @method approvalPass(ApprovalData $data)
 * @method approvalReject(ApprovalData $data)
 * @method approvalRevoke(ApprovalData $data)
 */
trait HasApproval
{

    public function isAllowApproval() : bool
    {
        if ($this->approval_status !== ApprovalStatusEnum::PROCESSING) {
            return false;
        }
        return true;
    }


    public function isAllowSubmitApproval() : bool
    {
        if (in_array($this->approval_status, [ApprovalStatusEnum::PASS, ApprovalStatusEnum::PROCESSING], true)) {
            return false;
        }
        return true;
    }


    /**
     * @return void
     * @throws ApprovalException
     */
    public function submitApproval() : void
    {
        if (!$this->isAllowSubmitApproval()) {
            throw new ApprovalException();
        }
        $this->approval_status = ApprovalStatusEnum::PROCESSING;

    }

    /**
     * @param  ApprovalData  $data
     *
     * @return void
     * @throws ApprovalException
     */
    public function handleApproval(ApprovalData $data) : void
    {
        if (!$this->isAllowApproval()) {
            throw new ApprovalException();
        }

        $this->approval_status = $data->approvalStatus;
        switch ($data->approvalStatus) {
            case ApprovalStatusEnum::PASS:

                if (method_exists($this, 'approvalPass')) {
                    $this->approvalPass($data);
                }
                break;
            case ApprovalStatusEnum::REJECT:
                if (method_exists($this, 'approvalReject')) {
                    $this->approvalReject($data);
                }
                break;
            case ApprovalStatusEnum::REVOKE:
                if (method_exists($this, 'approvalRevoke')) {
                    $this->approvalRevoke($data);
                }
                break;
            default:
                break;
        }
    }
}