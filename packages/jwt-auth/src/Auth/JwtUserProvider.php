<?php

namespace RedJasmine\JwtAuth\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use RedJasmine\JwtAuth\Models\JwtUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtUserProvider  implements UserProvider
{
    protected array $models = [];
    protected ?Hasher $hasher = null;

    public function __construct(?Hasher $hasher = null)
    {
        $this->hasher = $hasher;
    }



    /**
     * 通过ID检索用户
     */
    public function retrieveById($identifier): ?Authenticatable
    {

        return $this->createUserFromPayload($identifier);

    }

    /**
     * 如果需要，重新哈希密码
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // JWT认证不需要重新哈希密码
    }

    /**
     * 从token中检索用户，无需查询数据库
     */
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        dd($identifier, $token);
        try {
            // 设置token到JWT
            JWTAuth::setToken($token);

            // 解析token获取payload
            $payload = JWTAuth::getPayload();

            if (!$payload || !$payload->get('sub')) {
                return null;
            }

            // 获取用户类型和ID
            $sub = $payload->get('sub');
            $userType = $payload->get('user_type', 'user');
            $userId = is_array($sub) ? $sub['id'] : $sub;

            // 创建用户实例，不查询数据库
            return $this->createUserFromPayload($payload);

        } catch (JWTException $e) {
            return null;
        }
    }

    /**
     * 通过凭据检索用户（登录时使用）
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials) ||
            (count($credentials) === 1 && array_key_exists('password', $credentials))) {
            return null;
        }

        // 获取用户类型，默认为user
        $userType = $credentials['user_type'] ?? 'user';
        unset($credentials['user_type']);

        $modelClass = $this->getModelClass($userType);

        if (!$modelClass) {
            return null;
        }

        // 查询数据库获取用户
        $query = $modelClass::query();

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * 验证用户凭据
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'] ?? null;

        if (is_null($plain)) {
            return false;
        }

        // 如果没有设置hasher，尝试从用户模型获取
        $hasher = $this->hasher ?? app('hash');

        return $hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * 更新用户的"记住我"token
     */
    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // JWT认证不需要记住我token
        return;
    }

    /**
     * 根据用户类型获取模型类
     */
    protected function getModelClass(string $userType): ?string
    {
        return $this->getModels()[$userType]??null;

    }

    /**
     * 从JWT payload创建用户实例
     */
    protected function createUserFromPayload(\Tymon\JWTAuth\Payload $payload): ?Authenticatable
    {
        $sub = $payload->get('sub');
        $userId = is_array($sub) ? $sub['id'] : $sub;

        if (!$userId) {
            return null;
        }
        $jwt = app('tymon.jwt');




        // 获取用户类型
        $userType = $payload->get('user_type', 'user');



        // 构建用户属性
        $attributes = array_merge([
            'id' => $userId,
        ],Arr::except($payload->toArray(), $jwt->factory()->getDefaultClaims()));

        // 创建通用JWT用户实例
        $user = new JwtUser($attributes, $userType);

        return $user;
    }


    /**
     * 设置模型配置
     */
    public function setModels(array $models): void
    {
        $this->models = $models;
    }

    /**
     * 获取模型配置
     */
    public function getModels(): array
    {
        return $this->models;
    }
}
