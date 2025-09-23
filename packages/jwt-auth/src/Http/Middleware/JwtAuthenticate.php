<?php

namespace RedJasmine\JwtAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\JwtAuth\Helpers\JwtHelper;

class JwtAuthenticate
{
    /**
     * 处理传入的请求
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        $guard = $guard ?: 'api';

        if (!Auth::guard($guard)->check()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                [$guard],
                $this->redirectTo($request)
            );
        }

        return $next($request);
    }

    /**
     * 获取未认证时的重定向路径
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}

/**
 * 可选的用户类型验证中间件
 */
class JwtAuthenticateWithUserType extends JwtAuthenticate
{
    /**
     * 处理传入的请求，并验证用户类型
     */
    public function handle(Request $request, Closure $next, string $guard = null, string ...$allowedUserTypes): mixed
    {
        $guard = $guard ?: 'api';

        // 首先进行基本的JWT认证
        if (!Auth::guard($guard)->check()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                [$guard],
                $this->redirectTo($request)
            );
        }

        // 检查用户类型
        if (!empty($allowedUserTypes)) {
            $userType = JwtHelper::getUserTypeFromToken();

            if (!$userType) {
                throw new AuthenticationException(
                    'Unable to determine user type from token.',
                    [$guard]
                );
            }

            if (!in_array($userType, $allowedUserTypes)) {
                throw new AuthenticationException(
                    "User type '{$userType}' is not allowed for this resource. Allowed types: " . implode(', ', $allowedUserTypes),
                    [$guard]
                );
            }
        }

        return $next($request);
    }
}

/**
 * 基于当前用户的用户类型验证中间件
 */
class JwtAuthenticateWithCurrentUserType extends JwtAuthenticate
{
    /**
     * 处理传入的请求，并验证当前用户的用户类型
     */
    public function handle(Request $request, Closure $next, string $guard = null, string ...$allowedUserTypes): mixed
    {
        $guard = $guard ?: 'api';

        // 首先进行基本的JWT认证
        if (!Auth::guard($guard)->check()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                [$guard],
                $this->redirectTo($request)
            );
        }

        // 检查用户类型
        if (!empty($allowedUserTypes)) {
            $user = Auth::guard($guard)->user();

            if (!$user) {
                throw new AuthenticationException(
                    'Unable to get current user.',
                    [$guard]
                );
            }

            // 如果用户实现了UserInterface接口，使用getType方法
            if ($user instanceof \RedJasmine\Support\Contracts\UserInterface) {
                $userType = $user->getType();
            } else {
                // 否则尝试从token获取用户类型
                $userType = JwtHelper::getUserTypeFromToken();
            }

            if (!$userType) {
                throw new AuthenticationException(
                    'Unable to determine user type.',
                    [$guard]
                );
            }

            if (!in_array($userType, $allowedUserTypes)) {
                throw new AuthenticationException(
                    "User type '{$userType}' is not allowed for this resource. Allowed types: " . implode(', ', $allowedUserTypes),
                    [$guard]
                );
            }
        }

        return $next($request);
    }
}
