<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\Exceptions\UnauthorizedException;

class CheckCategoryOwner
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $categoryId = $request->route('id') ?? $request->route('category');

        if ($categoryId) {
            $category = AnnouncementCategory::findOrFail($categoryId);

            // 检查是否为分类的所有者
            if ($category->owner_id !== Auth::id()) {
                throw new UnauthorizedException('无权访问此分类');
            }
        }

        return $next($request);
    }
}
