<?php

namespace RedJasmine\Product\Tests\Fixtures\Property;

use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;

class ProductPropertyFaker
{

    public function data() : array
    {
        $data = [
            'name'     => fake()->word(),
            'type'     => fake()->randomElement(PropertyTypeEnum::values()),
            'sort'     => fake()->randomNumber(),
            'group_id' => 0,
            'expands'  => [],
            'status'   => PropertyStatusEnum::ENABLE->value
        ];

        if ($data['type'] === PropertyTypeEnum::TEXT->value) {
            $data['unit'] = fake()->randomElement([ 'Hz', 'mAh', '英寸', 'mm', 'W' ]);
        }

        return $data;
    }

    public function createCommand(array $data = []) : ProductPropertyCreateCommand
    {
        return ProductPropertyCreateCommand::validateAndCreate(array_merge($this->data(), $data));
    }


    public function updateCommand(array $data = []) : ProductPropertyUpdateCommand
    {
        return ProductPropertyUpdateCommand::validateAndCreate(array_merge($this->data(), $data));
    }

}
