<?php

namespace RedJasmine\Project\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Repositories\ProjectMemberRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProjectMemberRepository extends Repository implements ProjectMemberRepositoryInterface
{
    protected static string $modelClass = ProjectMember::class;

    public function findByProjectAndMember(string $projectId, UserInterface $member): ?ProjectMember
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->first();
    }

    public function findMembersByProject(string $projectId): Collection
    {
        return ProjectMember::where('project_id', $projectId)
            ->with(['member', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findMembersByUser(UserInterface $member): Collection
    {
        return ProjectMember::where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->with(['project', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function isMember(string $projectId, UserInterface $member): bool
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->exists();
    }

    public function getActiveMembersCount(string $projectId): int
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('status', 'active')
            ->whereNull('left_at')
            ->count();
    }

    public function findMembersByStatus(string $projectId, string $status): Collection
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('status', $status)
            ->with(['member', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function allowedFilters(?Query $query = null): array
    {
        return [
            'project_id',
            'member_type',
            'member_id',
            'status',
            'invited_by_type',
            'invited_by_id',
        ];
    }

    protected function allowedSorts(?Query $query = null): array
    {
        return [
            'id',
            'status',
            'joined_at',
            'left_at',
            'created_at',
            'updated_at',
        ];
    }

    protected function allowedIncludes(?Query $query = null): array
    {
        return [
            'project',
            'member',
            'inviter',
        ];
    }
}
