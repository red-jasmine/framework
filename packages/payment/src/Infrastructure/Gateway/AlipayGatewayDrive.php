<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Exception;
use Illuminate\Support\Carbon;
use Omnipay\Alipay\AopPageGateway;
use Omnipay\Alipay\Responses\AbstractResponse;
use Omnipay\Alipay\Responses\AopCompletePurchaseResponse;
use Omnipay\Alipay\Responses\AopTradeAppPayResponse;
use Omnipay\Alipay\Responses\AopTradeCreateResponse;
use Omnipay\Alipay\Responses\AopTradePagePayResponse;
use Omnipay\Alipay\Responses\AopTradePreCreateResponse;
use Omnipay\Alipay\Responses\AopTradeRefundQueryResponse;
use Omnipay\Alipay\Responses\AopTradeWapPayResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\PaymentTrigger;
use RedJasmine\Payment\Domain\Exceptions\ChannelGatewayException;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\ChannelSettleReceiverData;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelPurchaseResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQuery;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\GatewayDriveInterface;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\IdentityTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\PaymentTriggerTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use RuntimeException;
use Throwable;

/**
 * 渠道适配器
 */
class AlipayGatewayDrive implements GatewayDriveInterface
{



    public function initGateway(PaymentChannelData $paymentChannelData) : static
    {
        $this->paymentChannelData = $paymentChannelData;
        $this->channelProduct     = $paymentChannelData->channelProduct;
        $this->channelApp         = $paymentChannelData->channelApp;
        $gatewayName              = $this->channelProduct->gateway ?? 'Alipay_AopApp';
        $this->gateway            = $this->initChannelApp(Omnipay::create($gatewayName), $this->channelApp);

        return $this;

    }


    /**
     * @param GatewayInterface $gateway
     * @param ChannelApp $channelApp
     *
     * @return GatewayInterface
     * @throws InvalidRequestException
     */
    protected function initChannelApp(GatewayInterface $gateway, ChannelApp $channelApp) : GatewayInterface
    {
        /**
         * @var $gateway AopPageGateway
         */

        $gateway->setSignType($channelApp->sign_type);
        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);
        $gateway->setNotifyUrl(PaymentUrl::notifyUrl($channelApp));

        if ($channelApp->isSandbox()) {
            $gateway->sandbox();
        }
        switch ($channelApp->sign_method) {
            case SignMethodEnum::Secret:
                if (blank($channelApp->channel_public_key)) {
                    throw new RuntimeException('支付宝证书公钥不存在');
                }
                $gateway->setAlipayPublicKey($channelApp->channel_public_key);
                break;
            case SignMethodEnum::Cert:
                if (blank($channelApp->channel_root_cert)) {
                    throw new RuntimeException('支付宝证书根证书不存在');
                }
                if (blank($channelApp->channel_public_key_cert)) {
                    throw new RuntimeException('支付宝证书公钥不存在');
                }

                if (blank($channelApp->channel_app_public_key_cert)) {
                    throw new RuntimeException('支付宝证书应用公钥不存在');
                }
                $gateway->setAlipayRootCert($channelApp->channel_root_cert);
                $gateway->setAlipayPublicCert($channelApp->channel_public_key_cert);
                $gateway->setAppCert($channelApp->channel_app_public_key_cert);
                $gateway->setCheckAlipayPublicCert(true);
                break;
            default:
                throw new RuntimeException('不支持的签名方式');
        }
        // 内容加密
        if (filled($channelApp->encrypt_key)) {
            $gateway->setEncryptKey($channelApp->encrypt_key);
        }


        return $gateway;
    }

    /**
     * @param Trade $trade
     * @param Environment $environment
     *
     * @return ChannelPurchaseResult
     * @throws Throwable
     */
    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult
    {


        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        if (method_exists($gateway, 'setReturnUrl')) {
            $gateway->setReturnUrl(PaymentUrl::returnUrl($trade));
        }
        // TODO 更多参数
        $bizContent = [

            'out_trade_no'      => $trade->trade_no,
            'total_amount'      => $trade->amount->format(),
            'subject'           => $trade->subject,
            'product_code'      => $this->channelProduct->code,
            'merchant_order_no' => $trade->merchant_order_no,
            'extend_params'     => [
                'royalty_freeze' => $trade->is_settle_sharing
            ],
        ];

        $request = $gateway->purchase(
            [
                'biz_content' =>$bizContent
            ]
        );


        try {

            /**
             * @var $response AbstractResponse
             */
            $response = $request->send();

            $result = new ChannelPurchaseResult;

            $result->setSuccessFul(false);

            $result->setMessage($response->getMessage());
            $result->setCode($response->getCode());
            $result->setData($response->getData());

            if ($response->isSuccessful()) {
                $result->setSuccessFul(true);
                $paymentTrigger = new PaymentTrigger();

                if ($response instanceof AopTradePagePayResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::REDIRECT;
                    $paymentTrigger->content = $response->getRedirectUrl();

                }
                if ($response instanceof AopTradeWapPayResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::REDIRECT;
                    $paymentTrigger->content = $response->getRedirectUrl();
                }
                if ($response instanceof AopTradeAppPayResponse) {
                    $paymentTrigger->type = PaymentTriggerTypeEnum::APP;

                    $paymentTrigger->content = $response->getOrderString();
                }
                if ($response instanceof AopTradePreCreateResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::QR_CODE;
                    $paymentTrigger->content = $response->getQrCode();
                }
                if ($response instanceof AopTradeCreateResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::APPLET;
                    $paymentTrigger->content = $response->getTradeNo();
                }
                $result->paymentTrigger = $paymentTrigger;
            }
            return $result;
        } catch (Throwable $throwable) {
            report($throwable);
            throw $throwable;

        }

    }

    /**
     * 完成支付
     *
     * @param array $parameters
     *
     * @return ChannelTradeData
     * @throws InvalidRequestException
     */
    public function completePurchase(array $parameters = []) : ChannelTradeData
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $request = $gateway->completePurchase()->setParams($parameters['data']??[]);

        try {
            /**
             * @var $response AopCompletePurchaseResponse
             */
            $response = $request->send();


            if ($response->isPaid()) {
                $data = $response->getData();


                // 调用查询接口
                $queryResponse = $gateway->query([
                                                     'biz_content' => [
                                                         'trade_no'      => $data['trade_no'],
                                                         'query_options' => [
                                                             'buyer_user_type',
                                                             'buyer_open_id'
                                                         ],
                                                     ]
                                                 ])->send();


                if ($queryResponse->isSuccessful()) {
                    // 合并参数
                    $data = array_merge($data, $queryResponse->getAlipayResponse());
                }


                $channelTradeData                     = new  ChannelTradeData;
                $channelTradeData->originalParameters = $data;
                $channelTradeData->channelCode        = $this->channelApp->channel_code;
                $channelTradeData->channelMerchantId  = $this->channelApp->channel_merchant_id;
                $channelTradeData->channelAppId       = (string)$data['app_id'];
                $channelTradeData->tradeNo            = (string)$data['out_trade_no'];
                $channelTradeData->channelTradeNo     = (string)$data['trade_no'];
                $channelTradeData->amount             = new MoneyOld(bcmul($data['total_amount'], 100, 0));
                $channelTradeData->paymentAmount      = new MoneyOld(bcmul($data['total_amount'], 100, 0));
                $channelTradeData->status             = TradeStatusEnum::SUCCESS;
                $channelTradeData->payer              = Payer::from([
                                                                        'type'    => $data['buyer_user_type'] ?? null,
                                                                        'userId'  => $data['buyer_id'] ?? null,
                                                                        'account' => $data['buyer_logon_id'] ?? null,
                                                                        'openId'  => $data['buyer_open_id'] ?? null,
                                                                        'name'    => null,
                                                                    ]);
                $channelTradeData->paidTime           = Carbon::make($data['gmt_payment']);

                return $channelTradeData;
            }
        } catch (Exception $e) {
            throw $e;

        }
    }

    public function refund(Refund $refund) : ChannelRefundResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        $request = $gateway->refund([
                                        'biz_content' => [
                                            'refund_amount'       => $refund->refundAmount->format(),
                                            'out_request_no'      => $refund->refund_no,
                                            'out_trade_no'        => $refund->trade_no,
                                            'trade_no'            => $refund->channel_trade_no,
                                            'refund_reason'       => $refund->refund_reason,
                                            'refund_goods_detail' => [],

                                        ]
                                    ]);

        try {
            $response = $request->send();
            $data     = $response->getAlipayResponse();

            $result = new ChannelRefundResult;
            $result->setMessage($data['sub_msg'] ?? $response->getMessage());
            $result->setCode($response->getCode());
            $result->setSuccessFul(false);
            $result->setData($response->getData());
            // 请求成功
            if ($response->isSuccessful()) {
                $result->setSuccessFul(true);
            }
            return $result;
        } catch (Throwable $throwable) {
            throw  $throwable;
        }

    }

    public function refundQuery(Refund $refund) : ChannelRefundQueryResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        $request           = $gateway->refundQuery([
                                                       'biz_content' => [

                                                           'out_request_no' => $refund->refund_no,
                                                           'out_trade_no'   => $refund->trade_no,
                                                           'trade_no'       => $refund->channel_trade_no,
                                                           'query_options'  => [
                                                               'refund_royaltys',
                                                               'gmt_refund_pay',
                                                               'refund_detail_item_list',
                                                               'send_back_fee',
                                                               'deposit_back_info',
                                                               'refund_voucher_detail_list',
                                                               'pre_auth_cancel_fee',
                                                               'refund_hyb_amount',
                                                               'refund_charge_info_list',
                                                           ],
                                                       ]
                                                   ]);
        $channelRefundData = new ChannelRefundQueryResult();
        try {

            /**
             * @var $response AopTradeRefundQueryResponse
             */
            $response = $request->send();
            $channelRefundData->setSuccessFul(false);
            if (!$response->isSuccessful()) {
                throw new RuntimeException('退款失败');
            }
            $data = $response->getAlipayResponse();

            $channelRefundData->originalParameters = $data;
            $channelRefundData->setSuccessFul(true);
            $channelRefundData->status            = RefundStatusEnum::PROCESSING;
            $channelRefundData->tradeNo           = $data['out_trade_no'];
            $channelRefundData->refundNo          = $data['out_request_no'];
            $channelRefundData->channelTradeNo    = $data['trade_no'];
            $channelRefundData->channelAppId      = $this->channelApp->channel_app_id;
            $channelRefundData->channelMerchantId = $this->channelApp->channel_merchant_id;
            // 判断是否退款成功

            if (($data['refund_status'] ?? '') === 'REFUND_SUCCESS') {
                $channelRefundData->status       = RefundStatusEnum::SUCCESS;
                $channelRefundData->refundTime   = Carbon::make($data['gmt_refund_pay']);
                $channelRefundData->refundAmount = new MoneyOld(bcmul($data['refund_amount'], 100, 0));
            }

            return $channelRefundData;

        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    public function notifyResponse() : NotifyResponseInterface
    {
        return new   NotifyResponse();
    }

    /**
     * @throws Throwable
     */
    public function transfer(Transfer $transfer) : ChannelTransferResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        // 根据场景转换渠道场景


        //支付宝的会员ID: ALIPAY_USER_ID
        //支付宝登录号: ALIPAY_LOGON_ID
        //支付宝openid: ALIPAY_OPEN_ID

        //身份证: IDENTITY_CARD
        //护照: PASSPORT

        $content = [
            'out_biz_no'      => $transfer->transfer_no,
            'trans_amount'    => $transfer->amount->format(),
            'biz_scene'       => $this->transferScene($transfer->scene_code), // TODO 根据场景转换
            'product_code'    => $transfer->channel_product_code,
            'order_title'     => $transfer->subject,
            'payee_info'      => [
                'identity'      => $transfer->payee_identity_id,// TODO 转换
                'identity_type' => match ($transfer->payee_identity_type) {
                    IdentityTypeEnum::LOGIN_ID => 'ALIPAY_LOGON_ID',
                    IdentityTypeEnum::USER_ID => 'ALIPAY_USER_ID',
                    IdentityTypeEnum::OPEN_ID => 'ALIPAY_OPEN_ID',
                    default => null
                },
                'cert_no'       => $transfer->payee_cert_no,
                'cert_type'     => match ($transfer->payee_cert_type) {
                    CertTypeEnum::ID_CARD => 'IDENTITY_CARD',
                    CertTypeEnum::PASSPORT => 'PASSPORT',
                    default => null
                },
                'name'          => $transfer->payee_name,
            ],
            'remark'          => $transfer->description,
            'business_params' => '',
        ];

        $result = new ChannelTransferResult();
        try {
            $request  = $gateway->transfer([ 'biz_content' => $content ]);
            $response = $request->send();
        } catch (Throwable $throwable) {
            throw new ChannelGatewayException($throwable->getMessage() ?? 'gateway error');
        }
        // 网关调用异常
        if (!$response->isSuccessful() && is_null($response->getData())) {
            throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
        }
        // 业务失败
        if (!$response->isSuccessful()) {
            $result->setSuccessFul(false);
            $result->status = TransferStatusEnum::FAIL;
            $result->setMessage($response->getSubMessage());
            return $result;
        }

        // 业务成功
        $result->setSuccessFul(true);
        $result->status            = TransferStatusEnum::PROCESSING;
        $data                      = $response->getAlipayResponse();
        $result->channelTransferNo = $data['order_id'] ?? null;
        // 业务成功 ，而外状态
        if (($data['status'] ?? '') === 'SUCCESS') {
            $result->channelTransferNo = $data['order_id'];
            $result->transferTime      = Carbon::parse($data['trans_date']);
            $result->status            = TransferStatusEnum::SUCCESS;
        }
        return $result;
    }

    /**
     * @throws ChannelGatewayException
     */
    public function transferQuery(Transfer $transfer) : ChannelTransferQueryResult
    {
        // 创建网关
        // 构建数据
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;
        $content = [
            'out_biz_no'   => $transfer->transfer_no,
            'product_code' => $transfer->channel_product_code,
            'biz_scene'    => $this->transferScene($transfer->scene_code), // TODO 进行改造 需要存储
        ];
        $request = $gateway->transferQuery([ 'biz_content' => $content ]);

        try {
            $response = $request->send();
            // 网关调用异常
            if (!$response->isSuccessful() && is_null($response->getData())) {
                throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
            }
            $result = new ChannelTransferQueryResult();
            // 业务异常
            if (!$response->isSuccessful()) {
                $result->setSuccessFul(false);
                $result->status = TransferStatusEnum::FAIL;
                $result->setMessage($response->getSubMessage());
                return $result;
            }

            $result->setSuccessFul(true);
            $data                      = $response->getAlipayResponse();
            $result->channelTransferNo = $data['order_id'] ?? null;

            // 存储各种 资金流水信息
            $status = ($data['status'] ?? '');
            if ($data['pay_date'] ?? null) {
                $result->transferTime = Carbon::parse($data['pay_date']);
            }
            switch ($status) {
                case 'SUCCESS':
                    $result->status = TransferStatusEnum::SUCCESS;
                    break;
                case 'WAIT_PAY':
                    $result->status = TransferStatusEnum::PROCESSING;
                    break;
                case 'CLOSED':
                    $result->status = TransferStatusEnum::CLOSED;
                    break;
                case 'FAIL':
                    $result->status = TransferStatusEnum::FAIL;
                    break;
                case 'DEALING': // 待处理
                    $result->status = TransferStatusEnum::PROCESSING;
                    break;
                case 'REFUND':
                    $result->status = TransferStatusEnum::REFUND;
                    break;
            }

            return $result;


        } catch (ChannelGatewayException $channelGatewayException) {
            throw $channelGatewayException;
        } catch (Throwable $throwable) {
            report($throwable);
            throw new ChannelGatewayException($throwable->getMessage() ?? 'gateway error');
        }


    }


    protected function transferScene(TransferSceneEnum $transferScene) : string
    {
        // 转换通过 渠道配置信息转账

        //单笔无密转账到支付宝，B2C现金红包: DIRECT_TRANSFER
        //C2C现金红包-领红包: PERSONAL_COLLECTION
        //CAE代扣: CAE_TRANSFER
        //余额充值到记账本: DIRECT_ALLOCATION
        //资金调拨转账: DIRECT_ALLOCATION_TRANSFER
        //记账本间资金调拨: ENTRUST_ALLOCATION
        //调拨并转账: ENTRUST_ALLOCATION_TRANSFER
        //记账本代发: ENTRUST_TRANSFER
        //境外结汇入金: OVERSEA_FCY_TRANSFER
        //红包资金发放: THIRDPARTY_PERSONAL_COLLECTION
        //红包资金领取: THIRDPARTY_PERSONAL_COLLECTION_CONFIRM
        //大额无限付: UNLIMITED_PAY

        return match ($transferScene) {
            TransferSceneEnum::COMMISSION, TransferSceneEnum::MARKETING => 'PERSONAL_COLLECTION',
            TransferSceneEnum::CLAIMS, TransferSceneEnum::ADMINISTRATIVE => 'ENTRUST_TRANSFER',
            TransferSceneEnum::REIMBURSEMENT, TransferSceneEnum::SUBSIDY, TransferSceneEnum::REMUNERATION => 'THIRDPARTY_PERSONAL_COLLECTION',
            TransferSceneEnum::PROCUREMENT, TransferSceneEnum::SERVICE => 'UNLIMITED_PAY',
            default => 'DIRECT_TRANSFER',
        };

    }


    protected function buildRequestNo() : string
    {
        return date('YmdHis') . rand(1000, 9999);
    }

    /**
     * @param SettleReceiver $settleReceiver
     *
     * @return ChannelResult
     * @throws ChannelGatewayException
     */
    public function bindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $accountType = match ($settleReceiver->account_type) {
            AccountTypeEnum::LOGIN_ID => 'loginName',
            AccountTypeEnum::USER_ID => 'userId',
            AccountTypeEnum::OPEN_ID => 'openId',
            default => ''
        };
        $content     = [
            'out_request_no' => $this->buildRequestNo(),
            'receiver_list'  => [
                [
                    'name'    => $settleReceiver->name,
                    'type'    => $accountType,
                    'account' => $settleReceiver->account,
                    'memo'    => '',
                ],

            ],
        ];

        $request = $gateway->bindSettleRelation([ 'biz_content' => $content ]);
        try {
            $response = $request->send();
            // 网关调用异常

            if (!$response->isSuccessful() && is_null($response->getData())) {
                throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
            }
            $result = new ChannelResult();
            $result->setSuccessFul($response->isSuccessful());
            return $result;
        } catch (ChannelGatewayException $channelGatewayException) {

            throw $channelGatewayException;
        } catch (Throwable $throwable) {
            throw new ChannelGatewayException($throwable->getMessage());
        }

    }

    /**
     * @param SettleReceiver $settleReceiver
     *
     * @return ChannelResult
     * @throws ChannelGatewayException
     */
    public function unbindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $accountType = match ($settleReceiver->account_type) {
            AccountTypeEnum::LOGIN_ID => 'loginName',
            AccountTypeEnum::USER_ID => 'userId',
            AccountTypeEnum::OPEN_ID => 'openId',
            default => ''
        };
        $content     = [
            'out_request_no' => $this->buildRequestNo(),
            'receiver_list'  => [
                [
                    'name'    => $settleReceiver->name,
                    'type'    => $accountType,
                    'account' => $settleReceiver->account,
                    'memo'    => '',
                ],

            ],
        ];

        $request = $gateway->unbindSettleRelation([ 'biz_content' => $content ]);
        try {
            $response = $request->send();
            // 网关调用异常

            if (!$response->isSuccessful() && is_null($response->getData())) {
                throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
            }

            $result = new ChannelResult();
            $result->setSuccessFul($response->isSuccessful());
            return $result;
        } catch (ChannelGatewayException $channelGatewayException) {

            throw $channelGatewayException;
        } catch (Throwable $throwable) {
            throw new ChannelGatewayException($throwable->getMessage());
        }

    }

    public function querySettleReceivers(ChannelSettleReceiverQuery $query = new ChannelSettleReceiverQuery
    ) : ChannelSettleReceiverQueryResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $content = [
            'out_request_no' => $this->buildRequestNo(),
            'page_num'       => $query->page,
            'page_size'      => $query->pageSize,

        ];

        $request = $gateway->querySettleRelation([ 'biz_content' => $content ]);
        try {
            $response = $request->send();
            // 网关调用异常

            if (!$response->isSuccessful() && is_null($response->getData())) {
                throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
            }
            $data   = $response->getAlipayResponse();
            $result = new ChannelSettleReceiverQueryResult();
            $result->setSuccessFul($response->isSuccessful());
            $result->setCode($response->getCode());
            $result->setMessage($response->getMessage());
            $result->totalCount      = (int)($data['total_record_num'] ?? 0);
            $result->totalCount      = (int)($data['total_page_num'] ?? 0);
            $result->currentPageSize = (int)($data['current_page_size'] ?? 0);
            $result->currentPage     = (int)($data['current_page_num'] ?? 0);

            $receivers = $data['receiver_list'] ?? [];
            foreach ($receivers as &$receiver) {
                if (isset($receiver['account_open_id'])) {
                    $receiver['account_type'] = AccountTypeEnum::OPEN_ID;
                    $receiver['account']      = $receiver['account_open_id'];
                }
                if (isset($receiver['bind_login_name'])) {
                    $receiver['account_type'] = AccountTypeEnum::LOGIN_ID;
                    $receiver['account']      = $receiver['bind_login_name'];
                }
            }
            $result->receivers = ChannelSettleReceiverData::collect($receivers);


            return $result;
        } catch (ChannelGatewayException $channelGatewayException) {

            throw $channelGatewayException;
        } catch (Throwable $throwable) {
            throw new ChannelGatewayException($throwable->getMessage());
        }

    }


    protected function matchAccountType(AccountTypeEnum $accountType)
    {
        return match ($accountType) {
            AccountTypeEnum::LOGIN_ID => 'loginName',
            AccountTypeEnum::USER_ID => 'userId',
            AccountTypeEnum::OPEN_ID => 'openId',
            default => ''
        };
    }

    /**
     * @link  https://opendocs.alipay.com/open/c3b24498_alipay.trade.order.settle
     * @param Settle $settle
     * @return ChannelSettleResult
     * @throws ChannelGatewayException
     */
    public function settle(Settle $settle) : ChannelSettleResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $royaltyParameters = [];

        foreach ($settle->details as $detail) {
            $royaltyParameters[] = [
                'royalty_type'   => 'transfer',// 分账类型 transfer,replenish
                'trans_out'      => null,
                'trans_out_type' => null,
                'trans_in_type'  => $this->matchAccountType($detail->account_type),
                'trans_in'       => $detail->account,
                'trans_in_name'  => $detail->name,
                'amount'         => $detail->amount_value,
                'desc'           => $detail->subject,
                'royalty_scene'  => '其他', // 达人佣金、平台服务费、技术服务费、其他
            ];
        }

        $content = [
            'out_request_no'     => $settle->settle_no,
            'trade_no'           => $settle->channel_trade_no,
            'extend_params'      => [
                'royalty_finish' => false,
            ],
            'royalty_mode'       => 'async',
            'royalty_parameters' => $royaltyParameters,
        ];

        $request = $gateway->settle([ 'biz_content' => $content ]);
        try {
            $response = $request->send();
            // 网关调用异常

            if (!$response->isSuccessful() && is_null($response->getData())) {
                throw new ChannelGatewayException($response->getSubMessage() ?? $response->getMessage() ?? 'gateway error');
            }
            $data   = $response->getAlipayResponse();

            $result = new ChannelSettleResult();
            $result->setSuccessFul($response->isSuccessful());
            if ($response->isSuccessFul()) {
                $result->channelSettleNo = $data['settle_no'];
            }

            return $result;
        } catch (ChannelGatewayException $channelGatewayException) {
            throw $channelGatewayException;
        } catch (Throwable $throwable) {
            throw new ChannelGatewayException($throwable->getMessage());
        }


    }


}
