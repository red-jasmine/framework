<?php

namespace RedJasmine\JwtAuth\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Payload;

class JwtGuard implements StatefulGuard
{
    protected ?Authenticatable $user = null;
    protected UserProvider $provider;
    protected Request $request;
    protected JWT $jwt;

    public function __construct(
        UserProvider $provider,
        Request $request,
        JWT $jwt
    ) {
        $this->provider = $provider;
        $this->request = $request;
        $this->jwt = $jwt;
    }

    /**
     * 检查是否已设置用户
     */
    public function hasUser(): bool
    {
        return !is_null($this->user);
    }

    /**
     * 检查用户是否已认证
     */
    public function check(): bool
    {
        return !is_null($this->user());
    }

    /**
     * 检查用户是否未认证
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * 获取当前认证的用户
     */
    public function user(): ?Authenticatable
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        try {
            $token = $this->getTokenForRequest();

            if (!$token) {
                return null;
            }

            // 使用自定义UserProvider从token解析用户
            $this->user = $this->provider->retrieveByToken(null, $token);

            return $this->user;
        } catch (JWTException $e) {
            return null;
        }
    }

    /**
     * 获取当前用户的ID
     */
    public function id(): ?string
    {
        $user = $this->user();
        return $user ? $user->getAuthIdentifier() : null;
    }

    /**
     * 验证用户凭据
     */
    public function validate(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }

        return false;
    }

    /**
     * 设置用户
     */
    public function setUser(?Authenticatable $user): void
    {
        $this->user = $user;
    }

    /**
     * 登录用户
     */
    public function login(Authenticatable $user, $remember = false): void
    {
        // 推断用户类型
        $userType = $this->inferUserType($user);

        // 设置JWT自定义声明
        $customClaims = $this->buildCustomClaims($user, $userType);

        // 生成token
        JWTAuth::customClaims($customClaims)->fromUser($user);

        // 设置当前用户
        $this->setUser($user);
    }

    /**
     * 使用凭据尝试认证用户
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->login($user, $remember);
            return true;
        }

        return false;
    }

    /**
     * 使用凭据登录用户一次（无会话或cookie）
     */
    public function once(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }

        return false;
    }

    /**
     * 使用用户ID登录
     */
    public function loginUsingId($id, $remember = false): ?Authenticatable
    {
        $user = $this->provider->retrieveById($id);

        if ($user) {
            $this->login($user, $remember);
            return $user;
        }

        return null;
    }

    /**
     * 使用用户ID登录一次（无会话或cookie）
     */
    public function onceUsingId($id): ?Authenticatable
    {
        $user = $this->provider->retrieveById($id);

        if ($user) {
            $this->setUser($user);
            return $user;
        }

        return null;
    }

    /**
     * 检查用户是否通过"记住我"cookie认证
     */
    public function viaRemember(): bool
    {
        // JWT认证不使用记住我功能
        return false;
    }

    /**
     * 登录用户并生成token（自定义方法，支持用户类型）
     */
    public function loginWithType(Authenticatable $user, ?string $userType = null): string
    {
        // 如果没有指定用户类型，尝试从用户类名推断
        if (!$userType) {
            $userType = $this->inferUserType($user);
        }

        // 设置JWT自定义声明
        $customClaims = $this->buildCustomClaims($user, $userType);

        // 生成token
        $token = JWTAuth::customClaims($customClaims)->fromUser($user);

        // 设置当前用户
        $this->setUser($user);

        return $token;
    }

    /**
     * 登出用户
     */
    public function logout(): void
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            // 忽略登出异常
        }

        $this->setUser(null);
    }

    /**
     * 刷新token
     */
    public function refresh(): string
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());

            // 重新解析用户
            $this->user = null;
            $this->user();

            return $token;
        } catch (JWTException $e) {
            throw $e;
        }
    }

    /**
     * 从请求中获取token
     */
    protected function getTokenForRequest(): ?string
    {
        $token = $this->request->header('Authorization');

        if ($token && str_starts_with($token, 'Bearer ')) {
            return substr($token, 7);
        }

        return $this->request->query('token');
    }

    /**
     * 从用户类名推断用户类型
     */
    protected function inferUserType(Authenticatable $user): string
    {
        $className = class_basename($user);

        // 转换为小写作为用户类型
        return strtolower($className);
    }

    /**
     * 构建JWT自定义声明
     */
    protected function buildCustomClaims(Authenticatable $user, string $userType): array
    {
        $customClaims = [
            'user_type' => $userType,
            'custom_claims' => []
        ];

        // 添加用户属性到自定义声明
        $attributes = $user instanceof Model ? $user->getAttributes() : (array) $user;

        foreach ($attributes as $key => $value) {
            if (!in_array($key, ['id', 'password', 'remember_token'])) {
                $customClaims['custom_claims'][$key] = $value;
            }
        }

        return $customClaims;
    }

    /**
     * 获取JWT实例
     */
    public function getJWT(): JWT
    {
        return $this->jwt;
    }

    /**
     * 获取JWT payload
     */
    public function getPayload(): ?Payload
    {
        try {
            return JWTAuth::getPayload();
        } catch (JWTException $e) {
            return null;
        }
    }
}
