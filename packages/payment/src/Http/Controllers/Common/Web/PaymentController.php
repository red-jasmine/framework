<?php

namespace RedJasmine\Payment\Http\Controllers\Common\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Omnipay\Alipay\AopAppGateway;
use Omnipay\Omnipay;
use RedJasmine\Payment\Business\PaymentBusiness;

class PaymentController extends Controller
{

    public function __construct(public PaymentBusiness $business)
    {
    }


    /**
     * @param Request $request
     * @param int $id
     */
    public function paying(Request $request, int $id)
    {

        $payment = $this->business->paying($id);
        return view('red-jasmine.payment::payment', compact('payment'));
    }

}
