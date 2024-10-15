<?php

namespace RedJasmine\Payment;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RedJasmine\Payment\Contracts\PaymentInterface;
use RedJasmine\Payment\Contracts\PaymentTradeInterface;
use RedJasmine\Payment\Enums\PaymentStatus;
use RedJasmine\Payment\Exceptions\PaymentException;
use RedJasmine\Payment\Models\PaymentInfo;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

class Payment implements PaymentInterface
{
    /**
     * @param int $id
     * @return Models\Payment
     */
    public function find(int $id) : Models\Payment
    {
        return Models\Payment::findOrFail($id);
    }

    /**
     * 创建预支付DNA
     * @param PaymentTradeInterface $paymentTrade
     * @param UserInterface $owner
     * @return Models\Payment
     * @throws Exception
     * @throws Throwable
     */
    public function prePayment(PaymentTradeInterface $paymentTrade, UserInterface $owner) : Models\Payment
    {

        $subject                 = (string)($data['subject'] ?? '支付');
        $description             = (string)($data['description'] ?? '');
        $subject                 = Str::limit($subject, 30);
        $description             = Str::limit($description, 200);
        $amount                  = $paymentTrade->amount();
        $payment                 = new Models\Payment();
        $payment->id             = Snowflake::getInstance()->nextId();
        $payment->owner_type     = $owner->getUserType();
        $payment->owner_id      = $owner->getID();
        $payment->owner_nickname = $owner->getNickname();

        $payment->app_id         = $paymentTrade->appid();
        $payment->app_order_no   = $paymentTrade->orderNo();
        $payment->app_order_type = $paymentTrade->orderType();

        $payment->total_amount = bcadd($amount, 0, 2);
        $payment->tax_amount   = 0;
        $payment->pay_amount   = bcadd($amount, 0, 2);

        $payment->status       = PaymentStatus::NOT_PAY;
        $payment->create_time  = now();
        $payment->expired_time = $paymentTrade->expiredTime();

        $paymentInfo                   = new PaymentInfo();
        $paymentInfo->id               = $payment->id;
        $paymentInfo->return_url       = $paymentTrade->returnUrl();
        $paymentInfo->request_url      = (string)($paymentTrade->others()['request_url'] ?? '');
        $paymentInfo->notify_url       = (string)($paymentTrade->others()['notify_url'] ?? '');
        $paymentInfo->pass_back_params = (array)($paymentTrade->others()['pass_back_params'] ?? []);


        try {
            DB::beginTransaction();
            $payment->save();
            $paymentInfo->save();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            throw  $throwable;
        }

        return $payment;
    }

    /**
     * 获取支付方式
     * @param int $id
     * @return Models\Payment
     * @throws PaymentException
     */
    public function paying(int $id) : Models\Payment
    {
        $payment = $this->find($id);
        $this->isAllowPay($payment);

        return $payment;
    }

    /**
     * @throws PaymentException
     */
    protected function isAllowPay(Models\Payment $payment) : void
    {
        // 状态在未支付 或者支付中
        if ($payment->status !== PaymentStatus::NOT_PAY) {
            throw new PaymentException('当前状态不支持付款', PaymentException::PAYMENT_STATUS_ERROR);
        }
        // 过期时间
        if ($payment->expired_time <= now()) {
            throw new PaymentException('支付超时', PaymentException::PAYMENT_TIMEOUT);
        }

    }


    /**
     * 成功通知
     * @return mixed
     */
    public function notify() : mixed
    {
        // TODO: Implement notify() method.
    }

    public function redirect()
    {
        // TODO: Implement redirect() method.
    }

    /**
     * 支付 选择支付方式
     * @return mixed
     */
    public function payment() : mixed
    {
        // TODO: Implement payment() method.
    }


}
