<?php


use Cknow\Money\Money;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePaidCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Data\Trades\PaymentTradeResult;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Client;
use RedJasmine\Payment\Domain\Models\ValueObjects\Device;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;

use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {
    // 数据准备


    BaseDataFixtures::init($this);


    $this->tradeCommandService = app(TradeApplicationService::class);
    $this->tradeRepository     = app(TradeRepositoryInterface::class);

});

test('can paid a trade', function () {


    $channelTradeData = new TradePaidCommand();

    $trade = $this->tradeRepository->findByNo('20250807093410506181601954394');
    // TODO 需要设置正确的单号
    $channelTradeData->tradeNo           = $trade->trade_no;
    $channelTradeData->channelTradeNo    = fake()->numerify('channel-trade-no-##########');
    $channelTradeData->channelCode       = 'alipay';
    $channelTradeData->channelAppId      = fake()->numerify('channelAppId-##########');
    $channelTradeData->channelMerchantId = fake()->numerify('channelMerchantId-##########');
    $channelTradeData->paidTime          = now();
    $channelTradeData->paymentAmount     = $trade->amount;
    $channelTradeData->payer             = Payer::from([
        'type'    => fake()->randomElement(['private', 'company']),
        'name'    => fake()->name(),
        'account' => fake()->numerify('account-##########'),
        'openId'  => fake()->uuid(),
        'userId'  => fake()->uuid(),
    ]);


    $result = $this->tradeCommandService->paid($channelTradeData);

    $this->assertEquals(true, $result);

    $trade = $this->tradeRepository->findByNo($trade->trade_no);

    $this->assertEquals(TradeStatusEnum::SUCCESS, $trade->status, '支付状态不一致');
    $this->assertEquals($channelTradeData->channelTradeNo, $trade->channel_trade_no, '支付单号不一致');
    $this->assertEquals($channelTradeData->channelCode, $trade->channel_code, '支付渠道不一致');


    $this->expectException(PaymentException::class);

    $this->tradeCommandService->paid($channelTradeData);


});
