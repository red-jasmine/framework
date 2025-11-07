<?php

namespace RedJasmine\FilamentCore\Helpers;

use Filament\Pages\Page;
use Filament\Resources\Pages\Page as ResourcePage;

trait HasClusterSubNavigation
{
    /**
     * @return array<\Filament\Navigation\NavigationItem | \Filament\Navigation\NavigationGroup>
     */
    public static function getRecordSubNavigation(Page $page): array
    {
        $cluster = static::getCluster();


        if (! $cluster) {
            return [];
        }
        
        // 获取 Cluster 的所有组件
        $components = $cluster::getClusteredComponents();
        
        // 获取参数（对于记录页面，需要传递记录参数）
        $parameters = method_exists($page, 'getSubNavigationParameters') 
            ? $page->getSubNavigationParameters() 
            : [];
        
        // 生成导航项
        $items = [];
        
        foreach ($components as $component) {
            $isResourcePage = is_subclass_of($component, ResourcePage::class);
            
            $shouldRegisterNavigation = $isResourcePage ?
                $component::shouldRegisterNavigation($parameters) :
                $component::shouldRegisterNavigation();
            
            if (! $shouldRegisterNavigation) {
                continue;
            }
            
            $canAccess = $isResourcePage ?
                $component::canAccess($parameters) :
                $component::canAccess();
            
            if (! $canAccess) {
                continue;
            }
            
            $pageItems = $isResourcePage ?
                $component::getNavigationItems($parameters) :
                $component::getNavigationItems();
            
            $items = [
                ...$items,
                ...$pageItems,
            ];
        }
        
        return $items;
    }
}

