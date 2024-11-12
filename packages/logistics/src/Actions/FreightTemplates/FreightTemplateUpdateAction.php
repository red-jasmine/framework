<?php

namespace RedJasmine\Logistics\Actions\FreightTemplates;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RedJasmine\Logistics\DataTransferObjects\FreightTemplates\FreightTemplateDTO;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplateFeeRegion;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplateFreeRegion;
use RedJasmine\Logistics\FreightTemplateService;
use RedJasmine\Logistics\Pipelines\FreightTemplates\FreightTemplateValidatePipeline;
use RedJasmine\Support\Foundation\Service\Action;
use Throwable;

class FreightTemplateUpdateAction extends Action
{
    public FreightTemplateService $service;


    protected static array $commonPipes = [
        FreightTemplateValidatePipeline::class,
    ];

    protected ?string $pipelinesConfigKey = 'red-jasmine.logistics.pipelines.template.update';


    /**
     * @throws Throwable
     */
    public function execute(int $id, FreightTemplateDTO $DTO) : LogisticsFreightTemplate
    {
        $logisticsFreightTemplate = $this->service->find($id);
        $logisticsFreightTemplate->setDTO($DTO);
        $this->pipelines($logisticsFreightTemplate);
        $this->pipeline->before();
        try {
            DB::beginTransaction();
            $logisticsFreightTemplate = $this->pipeline->then(fn(LogisticsFreightTemplate $logisticsFreightTemplate) => $this->update($logisticsFreightTemplate, $DTO));
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

        $this->pipeline->after();

        return $logisticsFreightTemplate;
    }

    /**
     * @param LogisticsFreightTemplate $logisticsFreightTemplate
     * @param FreightTemplateDTO       $DTO
     *
     * @return LogisticsFreightTemplate
     * @throws Exception
     */
    protected function update(LogisticsFreightTemplate $logisticsFreightTemplate, FreightTemplateDTO $DTO) : LogisticsFreightTemplate
    {
        $logisticsFreightTemplate->updater     = $this->service->getOperator();
        $logisticsFreightTemplate->name        = $DTO->name;
        $logisticsFreightTemplate->is_free     = $DTO->isFree;
        $logisticsFreightTemplate->sort        = $DTO->sort;
        $logisticsFreightTemplate->charge_type = $DTO->chargeType;
        $logisticsFreightTemplate->feeRegions()->delete();
        $logisticsFreightTemplate->freeRegions()->delete();
        if ($logisticsFreightTemplate->is_free === false) {
            if ($feeRegions = $this->fillFeeRegions($DTO)) {
                $logisticsFreightTemplate->feeRegions()->saveMany($feeRegions);
            }
            if ($freeRegions = $this->fillFreeRegions($DTO)) {
                $logisticsFreightTemplate->freeRegions()->saveMany($freeRegions);
            }
        }
        $logisticsFreightTemplate->push();
        return $logisticsFreightTemplate;
    }

    protected function fillFeeRegions(FreightTemplateDTO $DTO) : ?Collection
    {

        if (!$DTO->feeRegions) {
            return null;
        }
        $regions = collect([]);
        foreach ($DTO->feeRegions as $feeRegion) {
            $logisticsFreightTemplateFeeRegion                 = new LogisticsFreightTemplateFeeRegion();
            $logisticsFreightTemplateFeeRegion->regions        = $feeRegion->regions;
            $logisticsFreightTemplateFeeRegion->start_standard = $feeRegion->startStandard;
            $logisticsFreightTemplateFeeRegion->start_fee      = $feeRegion->startFee;
            $logisticsFreightTemplateFeeRegion->add_standard   = $feeRegion->addStandard;
            $logisticsFreightTemplateFeeRegion->add_fee        = $feeRegion->addFee;

            $regions[] = $logisticsFreightTemplateFeeRegion;
        }

        return $regions;
    }

    protected function fillFreeRegions(FreightTemplateDTO $DTO) : ?Collection
    {

        if (!$DTO->freeRegions) {
            return null;
        }
        $regions = collect([]);
        foreach ($DTO->freeRegions as $feeRegion) {
            $logisticsFreightTemplateFreeRegion           = new LogisticsFreightTemplateFreeRegion();
            $logisticsFreightTemplateFreeRegion->regions  = $feeRegion->regions;
            $logisticsFreightTemplateFreeRegion->amount   = $feeRegion->amount;
            $logisticsFreightTemplateFreeRegion->quantity = $feeRegion->quantity;
            $regions[]                                    = $logisticsFreightTemplateFreeRegion;
        }

        return $regions;
    }
}
