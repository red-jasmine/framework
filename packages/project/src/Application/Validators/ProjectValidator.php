<?php

namespace RedJasmine\Project\Application\Validators;

use RedJasmine\Project\Application\Services\ProjectApplicationService;
use RedJasmine\Project\Domain\Models\Enums\ProjectStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectType;
use RedJasmine\Support\Contracts\UserInterface;

class ProjectValidator
{
    public function __construct(
        protected ProjectApplicationService $service
    ) {
    }

    /**
     * 验证项目创建数据
     */
    public function validateCreate(array $data): array
    {
        $errors = [];

        // 验证项目名称
        if (empty($data['name'])) {
            $errors['name'] = ['项目名称不能为空'];
        } elseif (strlen($data['name']) > 255) {
            $errors['name'] = ['项目名称长度不能超过255个字符'];
        }

        // 验证项目代码
        if (!empty($data['code'])) {
            if (!$this->service->codeGenerator->validate($data['code'])) {
                $errors['code'] = ['项目代码格式不正确'];
            } elseif ($this->service->codeExists($data['owner'], $data['code'])) {
                $errors['code'] = ['项目代码已存在'];
            }
        }

        // 验证项目类型
        if (!empty($data['project_type']) && !in_array($data['project_type'], ProjectType::values())) {
            $errors['project_type'] = ['项目类型不正确'];
        }

        // 验证父项目
        if (!empty($data['parent_id'])) {
            $parent = $this->service->find($data['parent_id']);
            if (!$parent) {
                $errors['parent_id'] = ['父项目不存在'];
            } elseif ($parent->owner_type !== $data['owner']->getType() || $parent->owner_id !== $data['owner']->getID()) {
                $errors['parent_id'] = ['父项目不属于当前用户'];
            }
        }

        return $errors;
    }

    /**
     * 验证项目更新数据
     */
    public function validateUpdate(array $data): array
    {
        $errors = [];

        // 验证项目存在
        $project = $this->service->find($data['id']);
        if (!$project) {
            $errors['id'] = ['项目不存在'];
            return $errors;
        }

        // 验证项目名称
        if (!empty($data['name']) && strlen($data['name']) > 255) {
            $errors['name'] = ['项目名称长度不能超过255个字符'];
        }

        // 验证项目代码
        if (!empty($data['code'])) {
            if (!$this->service->codeGenerator->validate($data['code'])) {
                $errors['code'] = ['项目代码格式不正确'];
            } elseif ($this->service->codeExists($data['owner'], $data['code'], $data['id'])) {
                $errors['code'] = ['项目代码已存在'];
            }
        }

        // 验证项目类型
        if (!empty($data['project_type']) && !in_array($data['project_type'], ProjectType::values())) {
            $errors['project_type'] = ['项目类型不正确'];
        }

        // 验证父项目
        if (!empty($data['parent_id'])) {
            if ($data['parent_id'] === $data['id']) {
                $errors['parent_id'] = ['项目不能设置自己为父项目'];
            } else {
                $parent = $this->service->find($data['parent_id']);
                if (!$parent) {
                    $errors['parent_id'] = ['父项目不存在'];
                } elseif ($parent->owner_type !== $data['owner']->getType() || $parent->owner_id !== $data['owner']->getID()) {
                    $errors['parent_id'] = ['父项目不属于当前用户'];
                }
            }
        }

        return $errors;
    }

    /**
     * 验证项目状态变更
     */
    public function validateStatusChange(string $projectId, string $newStatus, UserInterface $operator): array
    {
        $errors = [];

        $project = $this->service->find($projectId);
        if (!$project) {
            $errors['project_id'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $operator, 'project.edit')) {
            $errors['permission'] = ['没有权限修改项目状态'];
        }

        // 验证状态转换
        $currentStatus = $project->status;
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
            ProjectStatus::DRAFT->value => [ProjectStatus::ACTIVE->value, ProjectStatus::ARCHIVED->value],
            ProjectStatus::ACTIVE->value => [ProjectStatus::PAUSED->value, ProjectStatus::ARCHIVED->value],
            ProjectStatus::PAUSED->value => [ProjectStatus::ACTIVE->value, ProjectStatus::ARCHIVED->value],
            ProjectStatus::ARCHIVED->value => [], // 归档状态不能转换到其他状态
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}
