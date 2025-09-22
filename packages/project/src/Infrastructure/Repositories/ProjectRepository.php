<?php

namespace RedJasmine\Project\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Repositories\ProjectRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProjectRepository extends Repository implements ProjectRepositoryInterface
{
    protected static string $modelClass = Project::class;

    public function findByOwner(UserInterface $owner): Collection
    {
        return Project::where('owner_type', $owner->getType())
            ->where('owner_id', $owner->getID())
            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByCode(UserInterface $owner, string $code): ?Project
    {
        return Project::where('owner_type', $owner->getType())
            ->where('owner_id', $owner->getID())
            ->where('code', $code)
            ->first();
    }

    public function findMembers(string $projectId): Collection
    {
        return ProjectMember::where('project_id', $projectId)
            ->with(['member', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findRoles(string $projectId): Collection
    {
        return ProjectRole::where('project_id', $projectId)
            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findMemberByProjectAndMember(string $projectId, UserInterface $member): ?ProjectMember
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->first();
    }

    public function findRoleByProjectAndCode(string $projectId, string $code): ?ProjectRole
    {
        return ProjectRole::where('project_id', $projectId)
            ->where('code', $code)
            ->first();
    }

    public function findProjectsByMember(UserInterface $member): Collection
    {
        return Project::whereHas('members', function ($query) use ($member) {
            $query->where('member_type', $member->getType())
                ->where('member_id', $member->getID())
                ->whereNull('left_at');
        })->with(['owner', 'members' => function ($query) use ($member) {
            $query->where('member_type', $member->getType())
                ->where('member_id', $member->getID());
        }])->get();
    }


    public function isMember(string $projectId, UserInterface $member): bool
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('member_type', $member->getType())
            ->where('member_id', $member->getID())
            ->whereNull('left_at')
            ->exists();
    }

    public function codeExists(UserInterface $owner, string $code, ?string $excludeId = null): bool
    {
        $query = Project::where('owner_type', $owner->getType())
            ->where('owner_id', $owner->getID())
            ->where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getActiveMembersCount(string $projectId): int
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('status', 'active')
            ->whereNull('left_at')
            ->count();
    }

    public function getRolesCount(string $projectId): int
    {
        return ProjectRole::where('project_id', $projectId)
            ->where('status', 'active')
            ->count();
    }

    protected function allowedFilters(?Query $query = null): array
    {
        return [
            'owner_type',
            'owner_id',
            'parent_id',
            'name',
            'code',
            'project_type',
            'status',
        ];
    }

    protected function allowedSorts(?Query $query = null): array
    {
        return [
            'id',
            'name',
            'code',
            'sort',
            'created_at',
            'updated_at',
        ];
    }

    protected function allowedIncludes(?Query $query = null): array
    {
        return [
            'owner',
            'parent',
            'children',
            'members',
            'roles',
            'members.member',
        ];
    }
}
