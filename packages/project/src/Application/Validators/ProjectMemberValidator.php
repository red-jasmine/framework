<?php

namespace RedJasmine\Project\Application\Validators;

use RedJasmine\Project\Application\Services\ProjectApplicationService;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class ProjectMemberValidator
{
    public function __construct(
        protected ProjectApplicationService $service
    ) {
    }

    /**
     * 验证项目成员添加
     */
    public function validateAddMember(array $data): array
    {
        $errors = [];

        // 验证项目存在
        $project = $this->service->find($data['project_id']);
        if (!$project) {
            $errors['project_id'] = ['项目不存在'];
            return $errors;
        }

        // 验证成员是否已存在
        if ($this->service->memberRepository->isMember($data['project_id'], $data['member'])) {
            $errors['member'] = ['成员已存在'];
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $data['operator'], 'member.invite')) {
            $errors['permission'] = ['没有权限邀请成员'];
        }

        // 验证项目状态
        if (!$project->isActive()) {
            $errors['project_status'] = ['项目未激活，无法添加成员'];
        }

        return $errors;
    }

    /**
     * 验证项目成员移除
     */
    public function validateRemoveMember(string $memberId, UserInterface $operator): array
    {
        $errors = [];

        $member = $this->service->memberRepository->find($memberId);
        if (!$member) {
            $errors['member_id'] = ['项目成员不存在'];
            return $errors;
        }

        $project = $this->service->find($member->project_id);
        if (!$project) {
            $errors['project'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $operator, 'member.remove')) {
            $errors['permission'] = ['没有权限移除成员'];
        }

        // 验证不能移除自己
        if ($member->member_type === $operator->getType() && $member->member_id === $operator->getID()) {
            $errors['self_remove'] = ['不能移除自己'];
        }

        return $errors;
    }

    /**
     * 验证成员状态变更
     */
    public function validateStatusChange(string $memberId, string $newStatus, UserInterface $operator): array
    {
        $errors = [];

        $member = $this->service->memberRepository->find($memberId);
        if (!$member) {
            $errors['member_id'] = ['项目成员不存在'];
            return $errors;
        }

        $project = $this->service->find($member->project_id);
        if (!$project) {
            $errors['project'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $operator, 'member.manage')) {
            $errors['permission'] = ['没有权限管理成员状态'];
        }

        // 验证状态转换
        $currentStatus = $member->status;
        if (!$this->isValidStatusTransition($currentStatus, $newStatus)) {
            $errors['status'] = ['无效的状态转换'];
        }

        return $errors;
    }

    /**
     * 验证状态转换是否有效
     */
    protected function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $validTransitions = [
            ProjectMemberStatus::PENDING->value => [ProjectMemberStatus::ACTIVE->value, 'rejected'],
            ProjectMemberStatus::ACTIVE->value => [ProjectMemberStatus::PAUSED->value, ProjectMemberStatus::LEFT->value],
            ProjectMemberStatus::PAUSED->value => [ProjectMemberStatus::ACTIVE->value, ProjectMemberStatus::LEFT->value],
            'rejected' => ['pending'],
            ProjectMemberStatus::LEFT->value => [], // 离开状态不能转换到其他状态
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}
