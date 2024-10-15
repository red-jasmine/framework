<?php

namespace RedJasmine\Payment\Contracts;

use Illuminate\Support\Carbon;

interface PaymentTradeInterface
{

    /**
     * 系统内应用
     * @return string
     */
    public function appid() : string;

    /**
     * 业务单号
     * @return string
     */
    public function orderNo() : string;


    /**
     * 过期时间
     * @return Carbon
     */
    public function expiredTime():Carbon;

    /**
     * 订单类型
     * @return string|null
     */
    public function orderType():?string;

    /**
     * 金额
     * @return string
     */
    public function amount() : string;

    /**
     * 主题
     * @return string
     */
    public function subject() : string;

    /**
     * 前端重定向地址
     * @return string|null
     */
    public function returnUrl() : ?string;


    /**
     * 其他信息
     * @return array{request_url:string,pass_back_params:array}
     */
    public function others() : array;



}
