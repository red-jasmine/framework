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


    )
    {
    }

    public function index(Request $request)
    {

    }


    public function buy(Request $request)
    {
        $params  = $request->all();
        $command = ProductBuyCommand::from(array_merge($params, [ 'buyer' => $this->getOwner() ]));


        $this->commandService->buy($command);
        dd($command);
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
