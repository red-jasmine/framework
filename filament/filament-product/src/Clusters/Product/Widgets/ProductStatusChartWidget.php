<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Contracts\BelongsToOwnerInterface;

class ProductStatusChartWidget extends ChartWidget
{
    protected ?string $heading = '商品状态分布';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return true;
    }

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $owner = auth()->user();
        if($owner instanceof BelongsToOwnerInterface){
            $owner = $owner->owner();
        }
        
        $statusCounts = Product::query()
            ->where('owner_type', get_class($owner))
            ->where('owner_id', $owner->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = ProductStatusEnum::colors();

        foreach (ProductStatusEnum::cases() as $status) {
            $count = $statusCounts[$status->value] ?? 0;
            if ($count > 0) {
                $labels[] = ProductStatusEnum::labels()[$status->value];
                $data[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => '商品数量',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6', // primary
                        '#10b981', // success
                        '#f59e0b', // warning
                        '#6b7280', // gray
                        '#ef4444', // danger
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

