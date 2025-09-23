<?php

namespace RedJasmine\JwtAuth\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtHelper
{
    /**
     * 生成JWT token
     */
    public static function generateToken(Authenticatable $user, ?string $userType = null): string
    {
        $guard = Auth::guard('api');

        // 如果guard是JwtGuard实例，使用自定义方法
        if ($guard instanceof \RedJasmine\JwtAuth\Auth\JwtGuard) {
            $finalUserType = $userType ?: self::inferUserType($user);
            return $guard->loginWithType($user, $finalUserType);
        }

        // 否则使用标准的login方法
        $guard->login($user);
        return JWTAuth::fromUser($user);
    }

    /**
     * 从token获取用户信息
     */
    public static function getUserFromToken(?string $token = null): ?Authenticatable
    {
        if (!$token) {
            $token = self::getTokenFromRequest();
        }

        if (!$token) {
            return null;
        }

        try {
            JWTAuth::setToken($token);
            $guard = Auth::guard('api');
            return $guard->user();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 验证token是否有效
     */
    public static function validateToken(?string $token = null): bool
    {
        if (!$token) {
            $token = self::getTokenFromRequest();
        }

        if (!$token) {
            return false;
        }

        try {
            JWTAuth::setToken($token);
            JWTAuth::getPayload();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 刷新token
     */
    public static function refreshToken(?string $token = null): ?string
    {
        if (!$token) {
            $token = self::getTokenFromRequest();
        }

        if (!$token) {
            return null;
        }

        try {
            JWTAuth::setToken($token);
            return JWTAuth::refresh();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 使token失效
     */
    public static function invalidateToken(?string $token = null): bool
    {
        if (!$token) {
            $token = self::getTokenFromRequest();
        }

        if (!$token) {
            return false;
        }

        try {
            JWTAuth::setToken($token);
            JWTAuth::invalidate();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 从请求中获取token
     */
    public static function getTokenFromRequest(): ?string
    {
        $request = request();

        // 从Authorization头获取
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }

        // 从查询参数获取
        return $request->query('token');
    }

    /**
     * 获取token的payload
     */
    public static function getTokenPayload(?string $token = null): ?array
    {
        if (!$token) {
            $token = self::getTokenFromRequest();
        }

        if (!$token) {
            return null;
        }

        try {
            JWTAuth::setToken($token);
            return JWTAuth::getPayload()->toArray();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 获取token中的用户类型
     */
    public static function getUserTypeFromToken(?string $token = null): ?string
    {
        $payload = self::getTokenPayload($token);
        return $payload['user_type'] ?? null;
    }

    /**
     * 从用户类名推断用户类型
     */
    protected static function inferUserType(Authenticatable $user): string
    {
        $className = class_basename($user);
        return strtolower($className);
    }

    /**
     * 检查用户类型是否支持
     */
    public static function isUserTypeSupported(string $userType): bool
    {
        $models = Config::get('jwt-auth.models', []);
        return isset($models[$userType]);
    }

    /**
     * 获取支持的用户类型列表
     */
    public static function getSupportedUserTypes(): array
    {
        return array_keys(Config::get('jwt-auth.models', []));
    }

    /**
     * 获取用户类型对应的模型类
     */
    public static function getModelClass(string $userType): ?string
    {
        $models = Config::get('jwt-auth.models', []);
        return $models[$userType] ?? null;
    }
}
