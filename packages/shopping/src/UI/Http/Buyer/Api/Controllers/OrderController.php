<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Shopping\Application\Services\Orders\Commands\BuyCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\CheckCommand;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Resources\OrdersDataResource;
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
     * @return OrdersDataResource  返回计算后的订单信息
     */
    public function check(Request $request) : OrdersDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        // 从请求数据和当前用户信息中构建产品计算命令
        $command = CheckCommand::from($request);

        // 调用命令服务进行产品订单的计算
        $orders = $this->commandService->check($command);

        // 返回计算后的订单信息
        return new OrdersDataResource($orders);
    }


    public function buy(Request $request) : OrdersDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        // 从请求数据和当前用户信息中构建产品计算命令
        $command = BuyCommand::from($request);


        // 获取客户端信息 通过请求头部获取
        $command->clientIp      = $request->getClientIp();
        $command->clientType    = 'test';
        $command->clientVersion = '1.0.0';

        $orders = $this->commandService->buy($command);
        return new OrdersDataResource($orders);
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
