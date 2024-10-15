# Order

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require red-jasmine/order
```

## Usage


### 订单金额计算公式

```
  // 商品金额 (product_amount) = 价格 * 数量
  // 订单总金额 (total_amount)  = 商品金额 + 税费 + 调整费 - 单品优惠

  // 订单 总商品金额 (product_amount)  = 所有商品金额 之和
  // 订单 总金额 (total_amount)  = 所有商品总金额 之和  + 邮费 + 调整费 - 订单优惠
  // 订单 付款金额 (payment_amount) = 订单 总金额 - 抵扣优惠

```

### 订单查询
 - 详情
 - 列表
 - 搜索

### 订单操作
 - 创建
 - 发起支付
 - 支付成功
 - 发货
   - 部分发货
   - 


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email liushoukun66@gmail.com instead of using the issue tracker.

## Credits

- [liushoukun][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/red-jasmine/order.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/red-jasmine/order.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/red-jasmine/order/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/red-jasmine/order
[link-downloads]: https://packagist.org/packages/red-jasmine/order
[link-travis]: https://travis-ci.org/red-jasmine/order
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/red-jasmine
[link-contributors]: ../../contributors
