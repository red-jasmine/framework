<?php

namespace RedJasmine\Support\Foundation\Service;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

/**
 * @property Data|array $data
 */
trait HasData
{


    /**
     * 领域传输对象
     * @var ?string
     */
    protected ?string $dataClass = null;



    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data) : static
    {
        $this->data = $data;
        return  $this;
    }



    /**
     * 转换数据
     *
     * @param Data|array|null $data
     *
     * @return Data|null
     */
    protected function conversionData(Data|array $data = null) : ?Data
    {
        if (is_array($data)) {
            $data = $this->morphsData($data);
            $data = $this->dataWithOwner($data);
            $data = $this->getDataClass()::from($data);

        }
        return $data;
    }

    /**
     * @return string|null|Data
     */
    public function getDataClass() : ?string
    {
        return $this->dataClass;
    }

    public function setDataClass(?string $dataClass) : static
    {
        $this->dataClass = $dataClass;
        return $this;
    }


    protected function dataWithOwner(array $data) : array
    {
        if ($this->service::$autoModelWithOwner && !isset($data[$this->service::$modelOwnerKey])) {
            if ($this->service->getOwner() instanceof UserData) {
                $data[$this->service::$modelOwnerKey] = $this->service->getOwner()->toArray();
            } elseif ($this->service->getOwner() instanceof UserInterface) {
                $data[$this->service::$modelOwnerKey] = UserData::fromUserInterface($this->service->getOwner())->toArray();
            }
        }
        return $data;
    }

    protected function morphsData(array $data) : array
    {

        if(!$this->getDataClass()){
            return  $data;
        }

        if (!method_exists($this->getDataClass(), 'morphs')) {
            return $data;
        }
        $morphs = $this->getDataClass()::morphs();
        foreach ($morphs as $morph) {
            $data = $this->initMorphFromArray($data, $morph);
        }
        return $data;
    }

    protected function initMorphFromArray(array $data, string $morph) : array
    {
        $typeKey     = $morph . '_type';
        $idKey       = $morph . '_id';
        $nicknameKey = $morph . '_nickname';
        $avatarKey   = $morph . '_avatar';
        if (!isset($data[$morph]) && (isset($data[$typeKey]) || isset($data[$idKey]))) {
            $data[$morph] = [
                'id'       => (int)$data[$idKey],
                'type'     => $data[$typeKey],
                'nickname' => $data[$nicknameKey] ?? null,
                'avatar'   => $data[$avatarKey] ?? null,
            ];
        }
        return $data;
    }
}
