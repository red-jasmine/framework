<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;

class CheckTopicOwner
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $topicId = $request->route('topic');

        if (!$topicId) {
            return $next($request);
        }

        $topic = Topic::findOrFail($topicId);

        // 检查话题所有权
        if ($topic->owner_id !== auth('owner')->id()) {
            throw new AuthorizationException('无权访问此话题');
        }

        // 将话题绑定到请求中，避免重复查询
        $request->attributes->set('topic', $topic);

        return $next($request);
    }
}
