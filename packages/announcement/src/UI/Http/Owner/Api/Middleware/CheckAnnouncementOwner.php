<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Support\Exceptions\UnauthorizedException;

class CheckAnnouncementOwner
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $announcementId = $request->route('id') ?? $request->route('announcement');

        if ($announcementId) {
            $announcement = Announcement::findOrFail($announcementId);

            // 检查是否为公告的所有者
            if ($announcement->owner_id !== Auth::id()) {
                throw new UnauthorizedException('无权访问此公告');
            }
        }

        return $next($request);
    }
}
