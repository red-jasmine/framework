<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Middlewares;

use Closure;
use Illuminate\Http\Request;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Exceptions\UnauthorizedException;

class CheckPointsProductOwner
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $productId = $request->route('id');
        $product = PointsProduct::findOrFail($productId);
        
        // 检查当前用户是否为商品所属者
        $currentUser = auth()->guard($guards[0] ?? null)->user();
        if ($product->owner_id !== $currentUser->getId()) {
            throw new \Exception('无权访问此积分商品');
        }
        
        return $next($request);
    }
} 