<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Pages;


use Filament\Pages\Page;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use RedJasmine\FilamentProduct\Clusters\Product;

class Dashboard extends Page
{



    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    public static function getNavigationLabel() : string
    {
       return  '商品中心';
    }


    public function getTitle() : string|Htmlable
    {
        return  '商品中心';
    }

    protected static ?string $slug           = 'dashboard';
    protected static ?int    $navigationSort = -3;
    protected static ?string $cluster        = Product::class;


    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets() : array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getWidgets() : array
    {

         // 商品数量
         // 商品类型分布
         // 商品状态分布
         //  库存预警

        return [

        ];
    }

    public function getColumns() : int|string|array
    {
        return 2;
    }

}
