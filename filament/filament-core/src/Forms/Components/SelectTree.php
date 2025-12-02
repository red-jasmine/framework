<?php

namespace RedJasmine\FilamentCore\Forms\Components;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SelectTree extends \CodeWithDennis\FilamentSelectTree\SelectTree
{
    protected ?Closure $getOptionLabelUsing = null;

    protected bool $withTranslation = false;

    /**
     * 重写 buildTree 方法以支持自定义标签获取
     */
    protected function buildTree() : Collection
    {
        // 调用父类方法获取树结构
        $tree = parent::buildTree();

        // 如果没有设置自定义标签回调，直接返回
        if (!$this->getOptionLabelUsing) {
            return $tree;
        }

        // 需要访问原始结果集来获取 record 信息
        // 由于基类的 buildNode 是 private，我们需要重写 buildTreeFromResults
        // 但为了简化，我们可以通过反射或者重写整个构建流程

        // 实际上，最简单的方式是重写 buildTree 和 buildTreeFromResults，然后实现自己的 buildNode
        // 但用户说只需要扩展 buildNode，所以我们保持代码简洁

        return $this->buildTreeWithCustomLabel();
    }

    /**
     * 使用自定义标签构建树
     */
    private function buildTreeWithCustomLabel() : Collection
    {
        // 复制父类的 buildTree 逻辑，但使用自定义的 buildNode
        $nullParentQuery    = $this->getQuery()->clone()->where($this->getParentAttribute(), $this->getParentNullValue());
        $nonNullParentQuery = $this->getQuery()->clone()->whereNot($this->getParentAttribute(), $this->getParentNullValue());

        if ($this->modifyQueryUsing) {
            $nullParentQuery = $this->evaluate($this->modifyQueryUsing, ['query' => $nullParentQuery]);
        }

        if ($this->modifyChildQueryUsing) {
            $nonNullParentQuery = $this->evaluate($this->modifyChildQueryUsing, ['query' => $nonNullParentQuery]);
        }

        $withTrashed = $this->evaluate($this->withTrashed);
        if ($withTrashed) {
            $nullParentQuery->withTrashed($withTrashed);
            $nonNullParentQuery->withTrashed($withTrashed);
        }

        $nullParentResults    = $nullParentQuery->lazy();
        $nonNullParentResults = $nonNullParentQuery->lazy();
        $combinedResults      = $nullParentResults->concat($nonNullParentResults);

        if ($this->storeResults) {
            $this->results = $combinedResults;
        }

        return $this->buildTreeFromResults($combinedResults);
    }

    public function getQuery() : ?Builder
    {
        if (!is_null($this->query)) {
            return $this->evaluate($this->query);
        }

        $query = $this->getRelationship()->getRelated()->query();
        if ($this->withTranslation) {
            $query->withTranslation();
        }
        return $query;
    }

    public function withTranslation(bool $withTranslation = true) : static
    {
        $this->withTranslation = $withTranslation;
        $this->getOptionLabelUsing(fn($record) => $record->translate() ?
            $record->translate()->{$this->getTitleAttribute()}
            : $record->{$this->getTitleAttribute()}
        );
        return $this;
    }

    /**
     * 设置用于获取选项标签的回调函数
     *
     * @param  Closure|null  $callback
     *
     * @return static
     */
    public function getOptionLabelUsing(?Closure $callback) : static
    {
        $this->getOptionLabelUsing = $callback;

        return $this;
    }

    /**
     * 构建树结构（复制父类逻辑，使用自定义 buildNode）
     */
    private function buildTreeFromResults($results, $parent = null) : Collection
    {
        if ($parent == null || $parent == $this->getParentNullValue()) {
            $parent = $this->getParentNullValue() ?? $parent;
        }

        $tree        = collect();
        $resultMap   = [];
        $resultCache = [];

        foreach ($results as $result) {
            $resultKey                         = $this->getCustomKey($result);
            $resultCache[$resultKey]['in_set'] = 1;
            if (isset($resultCache[$resultKey]['children'])) {
                $resultMap[$resultKey] = $resultCache[$resultKey]['children'];
                unset($resultCache[$resultKey]['children']);
            }
            $parentKey = $result->{$this->getParentAttribute()};
            if (!isset($resultCache[$parentKey])) {
                $resultCache[$parentKey]['in_set']   = 0;
                $resultCache[$parentKey]['children'] = [];
            }
            if ($resultCache[$parentKey]['in_set']) {
                $resultMap[$parentKey][] = $result;
            } else {
                $resultCache[$parentKey]['children'][] = $result;
            }
        }

        $orphanedResults = array_map(
            fn($item) => $item['children'],
            array_filter($resultCache, fn($item) => !$item['in_set'])
        );

        $resultMap[$parent] = [];
        foreach ($orphanedResults as $orphanedResult) {
            $resultMap[$parent] += $orphanedResult;
        }

        $rootResults     = $resultMap[$parent] ?? [];
        $disabledOptions = $this->getDisabledOptions();
        $hiddenOptions   = $this->getHiddenOptions();

        foreach ($rootResults as $result) {
            $node = $this->buildNode($result, $resultMap, $disabledOptions, $hiddenOptions);
            $tree->push($node);
        }

        return $tree;
    }

    /**
     * 构建节点（扩展以支持自定义标签获取）
     */
    private function buildNode($result, $resultMap, $disabledOptions, $hiddenOptions) : array
    {
        $key = $this->getCustomKey($result);

        // 使用自定义回调获取标签，如果存在的话
        $label = $this->getOptionLabelUsing
            ? $this->evaluate($this->getOptionLabelUsing, [
                'record' => $result,
                'value'  => $key,
            ])
            : $result->{$this->getTitleAttribute()};

        $node = [
            'name'     => $label,
            'value'    => $key,
            'parent'   => (string) $result->{$this->getParentAttribute()},
            'disabled' => in_array($key, $disabledOptions),
            'hidden'   => in_array($key, $hiddenOptions),
        ];

        if (isset($resultMap[$key])) {
            $children = collect();
            foreach ($resultMap[$key] as $child) {
                if (in_array($this->getCustomKey($child), $hiddenOptions)) {
                    continue;
                }
                $childNode = $this->buildNode($child, $resultMap, $disabledOptions, $hiddenOptions);
                $children->push($childNode);
            }
            $node['children'] = $children->toArray();
        }

        return $node;
    }
}
