<?php

namespace RedJasmine\JwtAuth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use RedJasmine\JwtAuth\Auth\JwtGuard;
use RedJasmine\JwtAuth\Auth\JwtUserProvider;

class JwtAuthServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register(): void
    {
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../config/jwt-auth.php',
        //     'jwt-auth'
        // );
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../config/jwt-auth.php' => config_path('jwt-auth.php'),
        ], 'config');

        // 注册JWT认证驱动
        $this->registerJwtDriver();

        // 注册JWT用户提供者
        $this->registerJwtUserProvider();

        // 注册中间件
        $this->registerMiddleware();
    }

    /**
     * 注册JWT认证驱动
     */
    protected function registerJwtDriver(): void
    {


        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            $guard = new JwtGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });


    }

    /**
     * 注册JWT用户提供者
     */
    protected function registerJwtUserProvider(): void
    {
        Auth::provider('jwt', function ($app, array $config) {
            $provider = new JwtUserProvider($app['hash']);

            // 设置模型配置
            if (isset($config['models'])) {
                $provider->setModels($config['models']);
            }

            return $provider;
        });
    }

    /**
     * 注册中间件
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];

        // 注册JWT认证中间件
        $router->aliasMiddleware('jwt.auth', \RedJasmine\JwtAuth\Http\Middleware\JwtAuthenticate::class);

        // 注册JWT用户类型验证中间件（基于token）
        $router->aliasMiddleware('jwt.auth.type', \RedJasmine\JwtAuth\Http\Middleware\JwtAuthenticateWithUserType::class);

        // 注册JWT用户类型验证中间件（基于当前用户）
        $router->aliasMiddleware('jwt.auth.user.type', \RedJasmine\JwtAuth\Http\Middleware\JwtAuthenticateWithCurrentUserType::class);
    }
}
