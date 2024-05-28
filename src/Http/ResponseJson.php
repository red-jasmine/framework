<?php

namespace RedJasmine\Support\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ResponseJson
{


    private static function wrapData(mixed $data, string $message, int|string $code, array $errors = []) : array
    {
        $data = [
            'data'    => $data,
            'code'    => $code,
            'message' => $message,
        ];
        if (filled($errors)) {
            $data['errors'] = $errors;
        }
        return $data;
    }


    /**
     * 成功响应
     *
     * @param mixed|null $data
     * @param string     $message
     *
     * @return JsonResponse|JsonResource
     */
    public static function success(mixed $data = null, string $message = 'ok') : JsonResponse|JsonResource
    {
        if ($data instanceof JsonResource) {
            return $data;
        }
        return response()->json(self::wrapData($data, $message, 0));
    }

    /**
     * 失败响应
     *
     * @param string     $message
     * @param int|string $code
     * @param int        $statusCode
     * @param array      $errors
     * @param mixed      $data
     *
     * @return JsonResponse
     */
    public static function error(string $message = 'error', int|string $code = 100000, int $statusCode = 400, array $errors = [], mixed $data = null) : JsonResponse
    {

        return response()->json(self::wrapData($data, $message, $code, $errors))->setStatusCode($statusCode);
    }
}
