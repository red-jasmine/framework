<?php


use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use RedJasmine\Payment\Domain\Data\TradeData;

test('make a trade data', function () {


    $data = TradeData::from([
                                'currency' => 'CNY',
                                'amount'   => 1,
                            ]);

    $money      = new \Money\Money(102, new \Money\Currency('USD'));
    $currencies = new ISOCurrencies();

    $numberFormatter = new NumberFormatter('', NumberFormatter::CURRENCY);
    $moneyFormatter  = new IntlMoneyFormatter($numberFormatter, $currencies);

    dump( $moneyFormatter->format($money)); // outputs $1.00
    dump( $moneyFormatter->format($money)); // outputs $1.00


});
