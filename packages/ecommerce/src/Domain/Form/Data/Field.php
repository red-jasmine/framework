<?php

namespace RedJasmine\Ecommerce\Domain\Form\Data;


use RedJasmine\Ecommerce\Domain\Form\Models\Enums\FieldTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;

class Field extends Data
{
    public string $name;

    public string $label;

    public FieldTypeEnum $type;

    public ?string $default = null;

    public bool $isRequired = false;

    /**
     * @var Option[]
     */
    public ?array $options = [];
    // 占位符
    public ?string $placeholder = null;
    // hint
    public ?string $hint = null;

}
