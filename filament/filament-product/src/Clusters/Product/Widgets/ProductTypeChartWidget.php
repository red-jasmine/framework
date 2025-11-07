<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Product;

class ProductTypeChartWidget extends ChartWidget
{
    protected ?string $heading = '商品类型分布';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return true;
    }

    protected function getData(): array
    {
        $owner = auth()->user();

        $typeCounts = Product::query()
            // ->where('owner_type',$owner->getType())
            //->where('owner_id', $owner->getId())
            ->select('product_type', DB::raw('count(*) as count'))
            ->groupBy('product_type')
            ->pluck('count', 'product_type')
            ->toArray();

        $labels = [];
        $data = [];

        foreach (ProductTypeEnum::cases() as $type) {
            $count = $typeCounts[$type->value] ?? 0;
            if ($count > 0) {
                $labels[] = ProductTypeEnum::labels()[$type->value];
                $data[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => '商品数量',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6', // primary - 实物
                        '#10b981', // success - 虚拟
                        '#f59e0b', // warning - 服务
                        '#8b5cf6', // purple - 数字
                        '#ec4899', // pink - 卡券
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

