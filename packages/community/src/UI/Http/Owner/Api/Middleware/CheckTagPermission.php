<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class CheckTagPermission
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = auth('owner')->user();

        if (!$user || !$user->can('manage', 'topic-tag')) {
            throw new AuthorizationException('无权管理话题标签');
        }

        return $next($request);
    }
}
