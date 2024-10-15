<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers;


use Illuminate\Http\Request;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductCalculateCommand;
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



    /**
     * 计算产品订单
     *
     * 该方法主要用于计算产品订单，包括合并请求数据和当前用户信息来创建命令对象，
     * 并调用命令服务进行订单计算处理返回计算后的订单信息
     *
     * @param Request $request 请求对象，包含产品计算所需的数据
     * @return mixed 返回计算后的订单信息
     */
    public function calculate(Request $request)
    {
        // 从请求数据和当前用户信息中构建产品计算命令
        $command = ProductCalculateCommand::from(
            array_merge($request->all(),
                [
                    'buyer' => $this->getOwner()
                ]));

        // 调用命令服务进行产品订单的计算
        $orders  = $this->commandService->calculates($command);

        // 返回计算后的订单信息
        return $orders;
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
