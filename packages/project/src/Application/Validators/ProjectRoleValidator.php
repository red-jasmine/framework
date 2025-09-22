<?php

namespace RedJasmine\Project\Application\Validators;

use RedJasmine\Project\Application\Services\ProjectApplicationService;
use RedJasmine\Project\Domain\Models\Enums\ProjectRoleStatus;
use RedJasmine\Support\Contracts\UserInterface;

class ProjectRoleValidator
{
    public function __construct(
        protected ProjectApplicationService $service
    ) {
    }

    /**
     * 验证项目角色创建
     */
    public function validateCreate(array $data): array
    {
        $errors = [];

        // 验证项目存在
        $project = $this->service->find($data['project_id']);
        if (!$project) {
            $errors['project_id'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $data['creator'], 'role.create')) {
            $errors['permission'] = ['没有权限创建角色'];
        }

        // 验证角色名称
        if (empty($data['name'])) {
            $errors['name'] = ['角色名称不能为空'];
        } elseif (strlen($data['name']) > 255) {
            $errors['name'] = ['角色名称长度不能超过255个字符'];
        }

        // 验证角色代码
        if (empty($data['code'])) {
            $errors['code'] = ['角色代码不能为空'];
        } elseif (strlen($data['code']) > 100) {
            $errors['code'] = ['角色代码长度不能超过100个字符'];
        } elseif (!$this->isValidCode($data['code'])) {
            $errors['code'] = ['角色代码格式不正确，只能包含字母、数字和下划线'];
        } elseif ($this->service->roleRepository->codeExists($data['project_id'], $data['code'])) {
            $errors['code'] = ['角色代码已存在'];
        }

        // 验证权限数组
        if (!empty($data['permissions']) && !is_array($data['permissions'])) {
            $errors['permissions'] = ['权限必须是数组格式'];
        }

        return $errors;
    }

    /**
     * 验证项目角色更新
     */
    public function validateUpdate(array $data): array
    {
        $errors = [];

        // 验证角色存在
        $role = $this->service->roleRepository->find($data['id']);
        if (!$role) {
            $errors['id'] = ['项目角色不存在'];
            return $errors;
        }

        $project = $this->service->find($role->project_id);
        if (!$project) {
            $errors['project'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $data['operator'], 'role.edit')) {
            $errors['permission'] = ['没有权限编辑角色'];
        }

        // 验证系统角色
        if ($role->is_system) {
            $errors['system_role'] = ['系统角色不能编辑'];
        }

        // 验证角色名称
        if (!empty($data['name']) && strlen($data['name']) > 255) {
            $errors['name'] = ['角色名称长度不能超过255个字符'];
        }

        // 验证角色代码
        if (!empty($data['code'])) {
            if (strlen($data['code']) > 100) {
                $errors['code'] = ['角色代码长度不能超过100个字符'];
            } elseif (!$this->isValidCode($data['code'])) {
                $errors['code'] = ['角色代码格式不正确，只能包含字母、数字和下划线'];
            } elseif ($this->service->roleRepository->codeExists($data['project_id'], $data['code'], $data['id'])) {
                $errors['code'] = ['角色代码已存在'];
            }
        }

        // 验证权限数组
        if (!empty($data['permissions']) && !is_array($data['permissions'])) {
            $errors['permissions'] = ['权限必须是数组格式'];
        }

        return $errors;
    }

    /**
     * 验证项目角色删除
     */
    public function validateDelete(string $roleId, UserInterface $operator): array
    {
        $errors = [];

        $role = $this->service->roleRepository->find($roleId);
        if (!$role) {
            $errors['role_id'] = ['项目角色不存在'];
            return $errors;
        }

        $project = $this->service->find($role->project_id);
        if (!$project) {
            $errors['project'] = ['项目不存在'];
            return $errors;
        }

        // 验证权限
        if (!$this->service->hasPermission($project, $operator, 'role.delete')) {
            $errors['permission'] = ['没有权限删除角色'];
        }

        // 验证系统角色
        if ($role->is_system) {
            $errors['system_role'] = ['系统角色不能删除'];
        }

        // 验证角色是否被使用
        $memberCount = $this->service->memberRepository->findMembersByProject($role->project_id)
            ->where('role_id', $role->id)
            ->count();
        if ($memberCount > 0) {
            $errors['in_use'] = ['角色正在被使用，不能删除'];
        }

        return $errors;
    }

    /**
     * 验证角色代码格式
     */
    protected function isValidCode(string $code): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $code);
    }
}
