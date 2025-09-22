<?php

namespace RedJasmine\Project\Infrastructure\Helpers;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\Project;

class ProjectTreeBuilder
{
    /**
     * 构建项目树结构
     */
    public function buildTree(Collection $projects): Collection
    {
        $tree = collect();
        $projectMap = $projects->keyBy('id');

        foreach ($projects as $project) {
            if ($project->parent_id === null) {
                $tree->push($this->buildNode($project, $projectMap));
            }
        }

        return $tree;
    }

    /**
     * 构建单个节点及其子节点
     */
    protected function buildNode(Project $project, Collection $projectMap): array
    {
        $node = [
            'id' => $project->id,
            'name' => $project->name,
            'short_name' => $project->short_name,
            'code' => $project->code,
            'status' => $project->status,
            'level' => $this->calculateLevel($project, $projectMap),
            'children' => collect(),
        ];

        $children = $projectMap->where('parent_id', $project->id);
        foreach ($children as $child) {
            $node['children']->push($this->buildNode($child, $projectMap));
        }

        return $node;
    }

    /**
     * 计算项目层级
     */
    protected function calculateLevel(Project $project, Collection $projectMap): int
    {
        $level = 0;
        $current = $project;

        while ($current->parent_id !== null) {
            $level++;
            $current = $projectMap->get($current->parent_id);
            if (!$current) {
                break;
            }
        }

        return $level;
    }

    /**
     * 获取项目的所有祖先
     */
    public function getAncestors(Project $project, Collection $projectMap): Collection
    {
        $ancestors = collect();
        $current = $project;

        while ($current->parent_id !== null) {
            $parent = $projectMap->get($current->parent_id);
            if (!$parent) {
                break;
            }
            $ancestors->prepend($parent);
            $current = $parent;
        }

        return $ancestors;
    }

    /**
     * 获取项目的所有后代
     */
    public function getDescendants(Project $project, Collection $projectMap): Collection
    {
        $descendants = collect();
        $children = $projectMap->where('parent_id', $project->id);

        foreach ($children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($this->getDescendants($child, $projectMap));
        }

        return $descendants;
    }

    /**
     * 扁平化树结构
     */
    public function flattenTree(Collection $tree): Collection
    {
        $flattened = collect();

        foreach ($tree as $node) {
            $flattened->push($node);
            if (!empty($node['children'])) {
                $flattened = $flattened->merge($this->flattenTree(collect($node['children'])));
            }
        }

        return $flattened;
    }
}
