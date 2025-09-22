<?php

namespace RedJasmine\Project\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Repositories\ProjectRoleRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProjectRoleRepository extends Repository implements ProjectRoleRepositoryInterface
{
    protected static string $modelClass = ProjectRole::class;

    public function findByProjectAndCode(string $projectId, string $code): ?ProjectRole
    {
        return ProjectRole::where('project_id', $projectId)
            ->where('code', $code)
            ->first();
    }

    public function findRolesByProject(string $projectId): Collection
    {
        return ProjectRole::where('project_id', $projectId)
            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findSystemRoles(): Collection
    {
        return ProjectRole::where('is_system', true)
            ->where('status', 'active')
            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findRolesByStatus(string $projectId, string $status): Collection
    {
        return ProjectRole::where('project_id', $projectId)
            ->where('status', $status)
            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function codeExists(string $projectId, string $code, ?string $excludeId = null): bool
    {
        $query = ProjectRole::where('project_id', $projectId)
            ->where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
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
            'project_id',
            'name',
            'code',
            'is_system',
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
            'project',
        ];
    }
}
