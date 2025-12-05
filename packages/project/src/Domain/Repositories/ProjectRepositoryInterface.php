<?php

namespace RedJasmine\Project\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据所有者查找项目
     */
    public function findByOwner(UserInterface $owner): Collection;

    /**
     * 根据所有者类型、ID和代码查找项目
     */
    public function findByCode(UserInterface $owner, string $code): ?Project;

    /**
     * 查找项目的所有成员
     */
    public function findMembers(string $projectId): Collection;

    /**
     * 查找项目的所有角色
     */
    public function findRoles(string $projectId): Collection;

    /**
     * 根据项目和成员信息查找项目成员
     */
    public function findMemberByProjectAndMember(string $projectId, UserInterface $member): ?ProjectMember;

    /**
     * 根据项目和代码查找角色
     */
    public function findRoleByProjectAndCode(string $projectId, string $code): ?ProjectRole;

    /**
     * 查找用户参与的所有项目
     */
    public function findProjectsByMember(UserInterface $member): Collection;


    /**
     * 检查用户是否为项目成员
     */
    public function isMember(string $projectId, UserInterface $member): bool;

    /**
     * 检查项目代码是否已存在
     */
    public function codeExists(UserInterface $owner, string $code, ?string $excludeId = null): bool;

    /**
     * 获取项目的活跃成员数量
     */
    public function getActiveMembersCount(string $projectId): int;

    /**
     * 获取项目的角色数量
     */
    public function getRolesCount(string $projectId): int;
}
