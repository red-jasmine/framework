<?php

namespace RedJasmine\Payment;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Contracts\UserInterface;

class PaymentChannelApp
{


    /**
     * 查询应用
     * @param int $id
     * @return Models\PaymentChannelApp
     */
    public function find(int $id) : Models\PaymentChannelApp
    {
        return Models\PaymentChannelApp::findOrFail($id);
    }


    /**
     * 查询可用应用
     * @param UserInterface $owner
     * @return Collection|array|Models\PaymentChannelApp[]
     */
    public function allowApps(UserInterface $owner) : Collection|array
    {
        return Models\PaymentChannelApp::owner($owner)
                                       ->where('status', 1)
                                       ->select([ 'id', 'channel_type','modes'])->get();
    }

}
