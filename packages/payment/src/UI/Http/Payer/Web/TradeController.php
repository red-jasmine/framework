<?php

namespace RedJasmine\Payment\UI\Http\Payer\Web;

use Illuminate\Http\Request;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;

class TradeController extends Controller
{
    // TODO
    // 订单支付页
    // 支付返回页
    // 支付收单页
    // 商户收银台

    // 订单支付页面

    // 网页返回页面

    // 网页发起支付

    public function show($id, string $time, string $signature, Request $request)
    {
        // 查询当前订单数据
        PaymentUrl::validSignature(compact('id', 'time', 'signature'));
        // TODO
        // 查询
        //  展示 订单信息
        // 查询 支付结果状态
        // 进行 重定向 回调
        dd($id, $time, $signature);


    }


    public function pay($id)
    {

    }



}
