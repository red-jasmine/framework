<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 支付环境
 */
class Environment extends Data
{
    /**
     * 支付场景
     * 小程序下 用 JSAPI
     * 应用内 用 JSAPI
     * APP 内用 APP
     * H5 用 H5
     * WEB 用 WEB
     * @var SceneEnum
     */
    #[WithCast(EnumCast::class, SceneEnum::class)]
    public SceneEnum $scene;

    /**
     * 选择的支付方式
     * @var string|null
     */
    public ?string $method;


    /**
     * 设备
     * @var Device|null
     */
    public ?Device $device = null;


    /**
     * @var ?Client
     */
    public ?Client $client;

    /**
     * @var Sdk|null
     */
    public ?Sdk $sdk;


}
