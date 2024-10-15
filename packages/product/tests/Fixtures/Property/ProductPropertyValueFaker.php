<?php

namespace RedJasmine\Product\Tests\Fixtures\Property;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

class ProductPropertyValueFaker
{

    public function data() : array
    {
        return [
            'group_id' => 0,
            'name'     => fake()->word,
            'sort'     => fake()->randomNumber(),
            'status'   => PropertyStatusEnum::ENABLE->value,
            'expands'  => [],
        ];
    }

    public function createCommand(array $data) : ProductPropertyValueCreateCommand
    {

        return ProductPropertyValueCreateCommand::validateAndCreate(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data) : ProductPropertyValueUpdateCommand
    {
        return ProductPropertyValueUpdateCommand::validateAndCreate(array_merge($this->data(), $data));
    }

}
