<?php

namespace RedJasmine\Payment\Helpers;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Contracts\PaymentTradeInterface;

class PaymentTradeObjectBuilder implements PaymentTradeInterface
{

    public function __construct(public array $data)
    {
    }


    /**
     * 系统内应用
     * @return string
     */
    public function appid() : string
    {
        return (int)($this->data['app_id']);
    }

    /**
     * 业务单号
     * @return string
     */
    public function orderNo() : string
    {
        return (string)($this->data['order_no']);
    }

    /**
     * 过期时间
     * @return Carbon
     */
    public function expiredTime() : Carbon
    {
        return Carbon::parse($this->data['expired_time'] ?? now()->addMinutes(15)->toDateTimeString());
    }


    /**
     * 订单类型
     * @return string|null
     */
    public function orderType() : ?string
    {
        return (string)($this->data['order_type'] ?? '');
    }


    /**
     * 金额
     * @return string
     */
    public function amount() : string
    {
        return (string)($this->data['amount']);
    }

    /**
     * 主题
     * @return string
     */
    public function subject() : string
    {
        return (string)($this->data['subject']);
    }

    /**
     * 前端重定向地址
     * @return string|null
     */
    public function returnUrl() : ?string
    {
        return (string)($this->data['return_url'] ?? '');
    }


    public function others() : array
    {
        return [
            'request_url'      => '',
            'pass_back_params' => [],
        ];
    }


}
