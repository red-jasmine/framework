<?php

namespace RedJasmine\Support\Services;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class RequestIDService
{

    protected static string $fieldName = 'x-Request-ID';

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public static function boot() : void
    {
        $kernel = app()->make(Kernel::class);
        $kernel->pushMiddleware(__CLASS__);
    }

    /**
     * 中间件处理
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        try {
            $requestID = self::setRequestID($request);
        } catch (BindingResolutionException $e) {
            $requestID = '';
        }
        // 写入日志上下文
        self::withLogContext($requestID);
        $response = $next($request);
        // 写入响应
        return self::withResponse($response, $requestID);

    }

    /**
     * 设置请求ID
     *
     * @param Request|null $request
     *
     * @return string
     * @throws BindingResolutionException
     */
    public static function setRequestID(?Request $request = null) : string
    {
        $requestID = self::getRequestID($request);
        if (!$requestID) {
            $request   = $request ?: Container::getInstance()->make('request');
            $requestID = self::generateRequestID($request);
            $request->headers->set(self::getFieldName(), $requestID);
        }
        return $requestID;
    }

    /**
     * 获取请求ID
     *
     * @param Request|null $request
     *
     * @return string
     * @throws BindingResolutionException
     */
    public static function getRequestID(?Request $request = null) : string
    {
        $request = $request ?: Container::getInstance()->make('request');
        return (string)($request->header(self::getFieldName()) ?? '');
    }

    /**
     * @return string
     */
    public static function getFieldName() : string
    {
        return self::$fieldName;
    }

    /**
     * @param string $fieldName
     */
    public static function setFieldName(string $fieldName) : void
    {
        self::$fieldName = $fieldName;
    }

    /**
     * 生成请求ID
     *
     * @param Request $request
     *
     * @return string
     */
    public static function generateRequestID(Request $request) : string
    {
        return (string)(Str::uuid());
    }

    protected static function withLogContext($requestID) : void
    {
        Log::shareContext([ self::getFieldName() => $requestID ]);
    }

    protected static function withResponse($response, $requestID)
    {
        try {
            if ($response instanceof JsonResponse) {
                $response->header(self::getFieldName(), $requestID);
            }
        } catch (Throwable $throwable) {

        }


        return $response;
    }


}
