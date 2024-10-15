<?php

namespace RedJasmine\Address;

use Illuminate\Support\Facades\Validator;
use RedJasmine\Address\Domain\Models\Enums\AddressValidateLevel;
use RedJasmine\Address\Exceptions\AddressException;
use RedJasmine\Region\Enums\RegionLevel;
use RedJasmine\Region\Facades\Region;
use RedJasmine\Support\Foundation\Service\Service;

class Address extends Service
{

    /**
     * 创建地址
     *
     * @param array                $data
     * @param AddressValidateLevel $validateLevel
     *
     * @return \RedJasmine\Address\Domain\Models\Address
     * @throws AddressException
     */
    public function create(array $data, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT) : Domain\Models\Address
    {

        $data['is_default'] = (int)(boolean)(int)($data['is_default'] ?? 0);
        $data['sort']       = (int)($data['sort'] ?? 0);
        // 基础信息验证
        $data = $this->validator($data);
        // 验证省份
        $data    = $this->regionValidate($data, $validateLevel);
        $address = new Domain\Models\Address();

        $address->owner   = $this->getOwner();
        $address->creator = $this->getOperator();
        $address->fill($data);
        $address->save();
        return $address;
    }

    /**
     * 基础验证
     *
     * @param array $data
     *
     * @return array
     */
    public function validator(array $data = []) : array
    {
        $rules      = [
            'contacts' => [ 'required', 'max:30', ],
            'mobile'   => [ 'required', 'max:20', ],
            'address'  => [ 'sometimes', 'max:100', ],
            'type'     => [ 'sometimes', 'max:100', ],
            'tag'      => [ 'sometimes', 'max:10', ],
            'remarks'  => [ 'sometimes', 'max:100', ],
            'zip_code' => [ 'sometimes', 'max:6', ],
        ];
        $messages   = [
            'max' => ':attribute 长度不能超过:max个字符'
        ];
        $attributes = [
            'contacts' => '联系人',
            'mobile'   => '手机号',
            'address'  => '地址',
            'tag'      => '标签',
            'remarks'  => '备注',
            'zip_code' => '邮政编码',
        ];
        Validator::make($data, $rules, $messages, $attributes)->validate();

        return $data;
    }

    /**
     * 验证区域
     * @deprecated
     *
     * @param array                $data
     * @param AddressValidateLevel $validateLevel
     *
     * @return array
     * @throws AddressException
     */
    public function regionValidate(array $data, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT) : array
    {

        $regionRules = [
            [
                'field'           => 'country_id',
                'name'            => '国家',
                'parent_id_field' => '',
                'level'           => RegionLevel::COUNTRY->value,
                'validate_level'  => AddressValidateLevel::COUNTRY,
            ],
            [
                'field'           => 'province_id',
                'name'            => '省份',
                'parent_id_field' => '',
                'level'           => RegionLevel::PROVINCE->value,
                'validate_level'  => AddressValidateLevel::PROVINCE,
            ],
            [
                'field'           => 'city_id',
                'name'            => '城市',
                'parent_id_field' => 'province_id',
                'level'           => RegionLevel::CITY->value,
                'validate_level'  => AddressValidateLevel::CITY,
            ],
            [
                'field'           => 'district_id',
                'name'            => '区|县',
                'parent_id_field' => 'city_id',
                'level'           => RegionLevel::DISTRICT->value,
                'validate_level'  => AddressValidateLevel::DISTRICT,
            ],
            [
                'field'           => 'street_id',
                'name'            => '乡镇街道',
                'parent_id_field' => 'district_id',
                'level'           => RegionLevel::STREET->value,
                'validate_level'  => AddressValidateLevel::STREET,
            ],
        ];
        $levels      = [
            AddressValidateLevel::COUNTRY->value  => 'country',
            AddressValidateLevel::PROVINCE->value => 'province',
            AddressValidateLevel::CITY->value     => 'city',
            AddressValidateLevel::DISTRICT->value => 'district',
            AddressValidateLevel::STREET->value   => 'street',
        ];
        $id          = [];
        foreach ($regionRules as $rule) {
            if ($data[$rule['field']] ?? null) {
                $id[] = (int)$data[$rule['field']];
            }
        }

        $regions = Region::query($id)->keyBy('id')->all();

        // 判断是否为连续
        //$maps = collect($regions)->pluck('parent_id', 'id')->sortKeysDesc();
        foreach ($regions as $id => $region) {
            // 没有上级了
            if (!isset($regions[$region->parent_id]) && $region->parent_id !== 0) {
                throw new AddressException("地址选择不正确");
            }
        }
        foreach (collect($regions)->sortKeys()->values() as $index => $region) {
            $field        = $levels[($index)];
            $data[$field] = $region->name;
        }
        return $data;
    }

    /**
     * 更新地址
     *
     * @param int                  $id
     * @param array                $data
     * @param AddressValidateLevel $validateLevel
     *
     * @return \RedJasmine\Address\Domain\Models\Address
     * @throws AddressException
     */
    public function update(int $id, array $data, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT) : Domain\Models\Address
    {
        $data['is_default'] = (int)(boolean)(int)($data['is_default'] ?? 0);
        $data['sort']       = (int)($data['sort'] ?? 0);
        // 基础信息验证
        $data = $this->validator($data);
        // 验证省份
        $data    = $this->regionValidate($data, $validateLevel);
        $address = Domain\Models\Address::findOrFail($id);


        $address->updater = $this->getOperator();
        $address->fill($data);
        $address->save();
        return $address;
    }
}
