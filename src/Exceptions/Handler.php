<?php

namespace RedJasmine\Support\Exceptions;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function __construct(Container $container)
    {
        parent::__construct($container);

        // 业务异常 进行
        $this->ignore(CommonRuntimeException::class);
    }

    protected function convertExceptionToArray(Throwable $e) : array
    {
        $arrData = [
            'code'    => 500,
            'message' => 'Server Error !',
            'data'    => null,
        ];
        // TODO Notfound Model 进行处理


        if ($this->isHttpException($e)) {
            $arrData['code']    = $e->getStatusCode();
            $arrData['message'] = $e->getMessage();
        }

        if ($e instanceof AuthenticationException) {
            $arrData['code']    = 401;
            $arrData['message'] = '登入失效';

        }
        if ($e instanceof AbstractException) {
            $arrData['data']    = $e->getData();
            $arrData['code']    = $e->getCode();
            $arrData['message'] = $e->getMessage();
        }
        if (($e instanceof NotFoundHttpException) && $e->getPrevious() instanceof ModelNotFoundException) {
            $arrData['message'] = '未找到当前资源';
        }
        if ($e instanceof ValidationException) {
            $arrData['code']    = 422;
            $arrData['message'] = $e->getMessage();
            $arrData['errors']  = $e->errors();
        }

        if (config('app.debug')) {
            $arrData['exception'] = get_class($e);
            $arrData['message']   = $e->getMessage();
            $arrData['file']      = $e->getFile();
            $arrData['line']      = $e->getLine();
            $arrData['trace']     = collect($e->getTrace())->map(fn($trace) => Arr::except($trace, [ 'args' ]))->all();
        }


        return $arrData;
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request             $request
     * @param ValidationException $exception
     *
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception) : JsonResponse
    {

        return response()->json($this->convertExceptionToArray($exception), $exception->status);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception) : Response
    {
        return $this->shouldReturnJson($request, $exception)
            ? response()->json($this->convertExceptionToArray($exception), 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

}
