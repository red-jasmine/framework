<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

/**
 * 引入基础数据类，为设备类提供数据管理功能
 */

use RedJasmine\Support\Data\Data;

/**
 * 设备类，继承自Data，用于表示和操作支付设备的相关信息
 */
class Device extends Data
{


    /**
     * 操作系统，允许为空，表示设备的操作系统信息
     * @var string|null
     */
    public ?string $os;


    public ?string $version;
    /**
     * 设备品牌，允许为空，表示设备的品牌信息
     * @var ?string
     */
    public ?string $brand;

    /**
     * 设备型号，允许为空，表示设备的具体型号信息
     * @var ?string
     */
    public ?string $model;


    /**
     * 设备令牌，允许为空，用于唯一标识设备，通常用于安全和识别目的
     * @var ?string
     */
    public ?string $token;

    /**
     * 设备ID，允许为空，用于内部标识设备，不同于令牌，这个ID可能用于内部追踪和管理
     * @var ?string
     */
    public ?string $id;


    /**
     * 语言
     * @var string|null
     */
    public ?string $language;

    /**
     * 扩展信息
     * @var string|null
     */
    public ?string $extensions;
}
