<?php

namespace RedJasmine\Logistics\Pipelines\FreightTemplates;

use Closure;
use Illuminate\Support\Facades\Validator;
use RedJasmine\Logistics\Models\LogisticsFreightTemplate;

class FreightTemplateValidatePipeline
{

    public function rules() : array
    {
        return [
            'name'                         => [ 'required', 'string', 'max:64' ],
            'charge_type'                  => [ 'required' ],
            'is_free'                      => [ 'required', 'boolean' ],
            'sort'                         => [ 'required', 'integer', 'min:0' ],
            'fee_regions'                  => [ 'sometimes' ],
            'fee_regions.*.regions'        => [ 'required' ], // TODO 区域验证
            'fee_regions.*.start_standard' => [ 'required' ],
            'fee_regions.*.start_fee'      => [ 'required' ],
            'fee_regions.*.add_standard'   => [ 'required' ],
            'fee_regions.*.add_fee'        => [ 'required' ],
            'free_regions.*.regions'       => [ 'required' ], // TODO 区域验证
            'free_regions.*.quantity'      => [ 'required' ],
            'free_regions.*.amount'        => [ 'required' ],
        ];
    }

    public function messages() : array
    {

        return [];
    }

    public function attributes() : array
    {
        return [
            'name'        => '名称',
            'charge_type' => '计费类型',
            'is_free'     => '是否包邮',
            'sort'        => '排序',
        ];
    }

    public function before(LogisticsFreightTemplate $logisticsFreightTemplate, Closure $next)
    {
        $DTO       = $logisticsFreightTemplate->getDTO();
        $validator = Validator::make($DTO->toArray(), $this->rules(), $this->messages(), $this->attributes());
        $validator->validate();
        return $next($logisticsFreightTemplate);
    }

}
