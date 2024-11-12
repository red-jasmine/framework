<?php

namespace RedJasmine\Logistics;


use RedJasmine\Logistics\Actions\FreightTemplates\FreightTemplateUpdateAction;
use RedJasmine\Logistics\DataTransferObjects\FreightTemplates\FreightTemplateDTO;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Foundation\Service\ServiceAwareAction;

/**
 * @see FreightTemplateCreateAction::execute()
 * @method LogisticsFreightTemplate create(FreightTemplateDTO $DTO)
 * @see FreightTemplateUpdateAction::execute()
 * * @method LogisticsFreightTemplate update(int $id, FreightTemplateDTO $DTO)
 */
class FreightTemplateService extends Service implements ServiceAwareAction
{


    protected static ?string $actionsConfigKey = 'red-jasmine.logistics.actions.template';

    public $service;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }

    public function find(int $id) : LogisticsFreightTemplate
    {
        return LogisticsFreightTemplate::findOrFail($id);
    }



}
