<?php

// config for RedJasmine/Distribution
return [

    // 客户绑定模式 配置
    'bind_user'    => [
        // 有效期时间
        'expiration_time' => [
            'value' => 1,
            'unit'  => \RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum::DAY->value,
        ],
        // 保护期时间
        'protection_time' => [
            'value' => 2,
            'unit'  => \RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum::HOUR->value,
        ],

    ],

    // 抢客户
    'compete_user' => [
        /**
         * 抢客户模式
         * click 点击链接  触达 即可
         * order 点击链接后，还需要下单
         */
        'mode'             => 'click',

        /**
         * 抢客户 为  下单时
         * 点击链接后，需要在这个时间内 下单成功，即可抢客
         */
        'order_limit_time' => [
            'value' => 1,
            'unit'  => \RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum::DAY->value,
        ],
    ],


];
