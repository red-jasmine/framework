<?php

namespace RedJasmine\Community\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class TopicData extends Data
{


    public UserInterface $owner;

    public string $title;

    public string $content;

    public ?string $image = null;

    public ?string $description = null;

    public ?string $keywords = null;

    public ?int $categoryId = null;

    public int $sort = 0;

    public array $tags = [];
}