<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers;


use Illuminate\Http\Request;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Support\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function __construct(

        protected OrderCommandService $commandService,


    ) {
    }

    public function index(Request $request)
    {

    }


    public function buy(Request $request)
    {
        $command = ProductBuyCommand::from(array_merge($request->all(), ['buyer' => $this->getOwner()]));

        $command->clientIp      = $request->getClientIp();
        $command->clientType    = 'test';
        $command->clientVersion = '1.0.0';


        $orders = $this->commandService->buy($command);

        return $orders;
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
