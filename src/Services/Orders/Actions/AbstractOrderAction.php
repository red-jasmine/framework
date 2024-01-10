<?php

namespace RedJasmine\Order\Services\Orders\Actions;

use RedJasmine\Order\Enums\Orders\OrderStatusEnum;
use RedJasmine\Order\Enums\Orders\PaymentStatusEnum;
use RedJasmine\Order\Exceptions\OrderException;
use RedJasmine\Order\Models\Order;
use RedJasmine\Order\OrderService;
use RedJasmine\Support\Foundation\Service\Action;

class AbstractOrderAction extends Action
{
    protected ?OrderService $service;


    /**
     * @var array|null|OrderStatusEnum[]
     */
    protected ?array $allowOrderStatus = null;

    /**
     * @var array|null|PaymentStatusEnum[]
     */
    protected ?array $allowPaymentStatus = null;


    /**
     * @param Order $order
     *
     * @return bool
     * @throws OrderException
     */
    protected function allowStatus(Order $order) : bool
    {
        $this->checkStatus($order->order_status, $this->allowPaymentStatus);
        $this->checkStatus($order->payment_status, $this->allowPaymentStatus);
        return true;
    }

    /**
     * @param            $status
     * @param array|null $allowStatus
     *
     * @return bool
     * @throws OrderException
     */
    protected function checkStatus($status, ?array $allowStatus) : bool
    {
        if ($allowStatus === null) {
            return true;
        }
        if (!in_array($status, $allowStatus, true)) {
            throw new OrderException($status->label() . ' 不支持操作');
        }
        return true;
    }

}
