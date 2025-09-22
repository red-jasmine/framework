<?php

namespace RedJasmine\Project\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ProjectMemberRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据项目和成员信息查找项目成员
     */
    public function findByProjectAndMember(string $projectId, UserInterface $member): ?ProjectMember;

    /**
     * 查找项目的所有成员
     */
    public function findMembersByProject(string $projectId): Collection;

    /**
     * 查找用户参与的所有项目成员记录
     */
    public function findMembersByUser(UserInterface $member): Collection;

    /**
     * 检查用户是否为项目成员
     */
    public function isMember(string $projectId, UserInterface $member): bool;

    /**
     * 获取项目的活跃成员数量
     */
    public function getActiveMembersCount(string $projectId): int;

    /**
     * 根据状态查找项目成员
     */
    public function findMembersByStatus(string $projectId, string $status): Collection;
}
