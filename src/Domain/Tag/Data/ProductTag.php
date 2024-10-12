<?php

namespace RedJasmine\Product\Domain\Tag\Data;

use RedJasmine\Product\Domain\Tag\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class ProductTag extends Data
{

    public UserInterface $owner;
    public string        $name;
    public ?string       $description;
    public bool          $isShow     = false;
    public bool          $isPublic = false;
    public TagStatusEnum $status     = TagStatusEnum::ENABLE;
    public int           $sort       = 0;
    public ?string       $cluster    = null;
    public ?string       $icon       = null;
    public ?string       $color      = null;


}
