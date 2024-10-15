<?php

namespace RedJasmine\Payment\Business;

use RedJasmine\Payment\Facades\PaymentChannelApp;
use RedJasmine\Payment\Models\Payment;
use RedJasmine\Support\Contracts\UserInterface;

class PaymentBusiness
{


    public function paying(int $id) : Payment
    {
        $payment = \RedJasmine\Payment\Facades\Payment::paying($id);

        $channels          = $this->channels($payment->getOwner());
        $payment->channels = $channels;

        return $payment;
    }

    /**
     * 支持的渠道类表
     * @param UserInterface $owner
     * @return array
     */
    public function channels(UserInterface $owner) : array
    {

        $apps          = PaymentChannelApp::allowApps($owner);
        $allowChannels = [];
        $allChannels   = config('payment.channels');
        foreach ($apps as $app) {
            $allowChannels[$app->channel_type]['modes'] = array_unique(array_merge($allowChannels[$app->channel_type]['modes'] ?? [], $app->modes));
            $allowChannels[$app->channel_type]['name']  = $allChannels[$app->channel_type]['name'];
            $allowChannels[$app->channel_type]['icon']  = $allChannels[$app->channel_type]['icon'];
        }
        return $allowChannels;
    }


}
