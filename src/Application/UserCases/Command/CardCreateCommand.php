<?php

namespace RedJasmine\Card\Application\UserCases\Command;


use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Contracts\UserInterface;

class CardCreateCommand extends Command
{

    public UserInterface $owner;

    public string $productType;

    public int $productId;

    public int $skuId;

    public CardStatus $status = CardStatus::UNSOLD;

    public string $content;

    public ?string $remarks;


}
