<?php

namespace RedJasmine\Address\Application\Services\Hooks;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use RedJasmine\Address\Domain\Data\AddressData;
use RedJasmine\Region\Application\Services\Country\CountryApplicationService;
use RedJasmine\Region\Application\Services\Country\Queries\CountryFindQuery;
use RedJasmine\Region\Application\Services\Region\Queries\RegionPaginateQuery;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;
use RedJasmine\Support\Application\HandleContext;

class AddressRegionHook
{
    public function __construct(
        protected CountryApplicationService $countryApplication,
        protected RegionApplicationService $regionApplication,
    ) {
    }


    public function handle(HandleContext $context, Closure $next)
    {


        /**
         * @var AddressData $command
         */
        $command = $context->getCommand();

        // 验证 国家
        $this->validateCountry($command);


        // 验证 地区数据  // 省市区 三级

        $this->validateRegion($command);


        return $next($context);
    }

    protected function validateCountry(AddressData $command) : void
    {
        // 如果传输了 CODE  已 CODE 为准
        if (filled($command->countryCode)) {

            try {
                $countryQuery         = CountryFindQuery::from([
                    'code' => $command->countryCode,
                ]);
                $country              = $this->countryApplication->find($countryQuery);
                $command->countryCode = $country->code;
                $command->country     = $country->name;
            } catch (ModelNotFoundException $foundException) {

                throw  ValidationException::withMessages([
                    'country_code' => [
                        '国家代码错误'
                    ],
                ]);
            }

        }

    }


    protected function validateRegion(AddressData $command) : void
    {
        $regions      = [];
        $regionFields = [
            'provinceCode' => 'province',
            'cityCode'     => 'city',
            'districtCode' => 'district',
            'streetCode'   => 'street',
            'villageCode'  => 'village',
        ];

        // 收集所有有值的区域代码
        foreach ($regionFields as $codeField => $nameField) {
            if (filled($command->$codeField)) {
                $regions[] = $command->$codeField;
            }
        }

        // 如果没有区域代码，直接返回
        if (count($regions) === 0) {
            return;
        }

        // 查询区域信息
        $query  = RegionPaginateQuery::from(['code' => $regions]);
        $result = $this->regionApplication->paginate($query);

        // 验证查询结果数量是否匹配
        if (count($result->items()) !== count($regions)) {
            throw ValidationException::withMessages([
                'region_codes' => ['代码错误'],
            ]);
        }

        $regionModels = collect($result->items())->keyBy('code');

        // 更新区域名称
        foreach ($regionFields as $codeField => $nameField) {
            if (filled($command->$codeField) && isset($regionModels[$command->$codeField])) {
                $command->$nameField = $regionModels[$command->$codeField]->name;
            }
        }

        // 验证区域层级关系
        foreach ($regions as $index => $code) {
            $parentCode  = $regions[$index - 1] ?? '0';
            $regionModel = $regionModels[$code];

            if ($regionModel->parent_code !== $parentCode) {
                throw ValidationException::withMessages([
                    'region_codes' => ['无法关联'],
                ]);
            }
        }
    }

}