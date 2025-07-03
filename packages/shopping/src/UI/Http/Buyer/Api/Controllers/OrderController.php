<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers;


use Illuminate\Http\Request;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductCalculateCommand;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Support\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function __construct(

        protected ShoppingOrderCommandService $commandService,


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
     * @param  Request  $request  请求对象，包含产品计算所需的数据
     *
     * @return mixed 返回计算后的订单信息
     */
    public function calculate(Request $request)
    {
        $request->offsetSet('buyer', $this->getOwner());
        // 从请求数据和当前用户信息中构建产品计算命令
        $command = ProductCalculateCommand::from($request);

        // 调用命令服务进行产品订单的计算
        $orders = $this->commandService->calculates($command);

        // 返回计算后的订单信息
        return $orders;
    }


    public function buy(Request $request)
    {
        $request->offsetSet('buyer', $this->getOwner());
        // 从请求数据和当前用户信息中构建产品计算命令
        $command = ProductBuyCommand::from($request);


        // 获取客户端信息 通过请求头部获取
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
