<?php

namespace RedJasmine\JwtAuth\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\JwtAuth\Helpers\JwtHelper;

/**
 * JWT认证门面
 *
 * @method static string generateToken(\Illuminate\Contracts\Auth\Authenticatable $user, ?string $userType = null)
 * @method static ?\Illuminate\Contracts\Auth\Authenticatable getUserFromToken(?string $token = null)
 * @method static bool validateToken(?string $token = null)
 * @method static ?string refreshToken(?string $token = null)
 * @method static bool invalidateToken(?string $token = null)
 * @method static ?string getTokenFromRequest()
 * @method static ?array getTokenPayload(?string $token = null)
 * @method static ?string getUserTypeFromToken(?string $token = null)
 * @method static bool isUserTypeSupported(string $userType)
 * @method static array getSupportedUserTypes()
 * @method static ?string getModelClass(string $userType)
 *
 * @see \RedJasmine\JwtAuth\Helpers\JwtHelper
 */
class JwtAuth extends Facade
{
    /**
     * 获取组件的注册名称
     */
    protected static function getFacadeAccessor(): string
    {
        return 'red-jasmine.jwt-auth';
    }

    /**
     * 代理方法调用到JwtHelper
     */
    public static function __callStatic($method, $args)
    {
        return JwtHelper::$method(...$args);
    }
}
