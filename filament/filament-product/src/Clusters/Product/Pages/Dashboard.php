<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Widgets\ProductStatsOverviewWidget;
use RedJasmine\FilamentProduct\Clusters\Product\Widgets\StockAlarmWidget;
use RedJasmine\FilamentProduct\Clusters\Product\Widgets\TopSellingProductsWidget;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    public static function getNavigationLabel() : string
    {
        return '商品中心';
    }

    public function getTitle() : string|Htmlable
    {
        return '商品中心';
    }

    protected static ?string $slug           = 'dashboard';
    protected static ?int    $navigationSort = -3;
    protected static ?string $cluster        = Product::class;

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    protected function getWidgets() : array
    {
        return [
            ProductStatsOverviewWidget::class,
            // ProductStatusChartWidget::class,
            TopSellingProductsWidget::class,
            StockAlarmWidget::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns() : int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function content(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Grid::make($this->getColumns())
                    ->schema(fn() : array => $this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }
}
