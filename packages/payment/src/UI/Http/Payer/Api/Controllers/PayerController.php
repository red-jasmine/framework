<?php

namespace RedJasmine\Payment\UI\Http\Payer\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Payment\Application\Services\Payer\Commands\PayerLoginCommand;
use RedJasmine\Payment\Application\Services\Payer\PayerApplicationService;

class PayerController extends Controller
{

    public function __construct(protected PayerApplicationService $service)
    {
    }


    public function info(Request $request)
    {

        $command = PayerLoginCommand::from($request);


        $result = $this->service->login($command);

        return static::success($result);

    }

}