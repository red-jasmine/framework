<?php

namespace RedJasmine\Interaction\Domain\Types;

use RedJasmine\Interaction\Domain\Contracts\InteractionTypeLimiterConfigInterface;

class BaseInteractionTypeLimiterConfig implements InteractionTypeLimiterConfigInterface
{

    public function __construct(protected array $config = [])
    {
    }

    public function unique() : bool
    {
        return $this->config['unique'] ?? true;
    }

    public function once() : int
    {
        return (int) ($this->config['once'] ?? 1);
    }

    public function total() : ?int
    {
        return $this->config['total'] ?? null;
    }

    public function interval() : ?int
    {
        return $this->config['interval'] ?? 120;
    }

    public function totals() : ?array
    {
        return $this->config['totals'] ?? null;
    }


}