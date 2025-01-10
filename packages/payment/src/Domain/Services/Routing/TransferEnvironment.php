<?php

namespace RedJasmine\Payment\Domain\Services\Routing;

use Dflydev\DotAccessData\Data;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class TransferEnvironment extends Data
{

    #[WithCast(EnumCast::class, TransferSceneEnum::class)]
    public TransferSceneEnum $sceneCode = TransferSceneEnum::TRANSFER;

    /**
     * @var string
     */
    public string $methodCode;
    /**
     * 渠道应用ID
     * @var ?string
     */
    public ?string $channelAppId = null;

}
