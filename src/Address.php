<?php

namespace RedJasmine\Address;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RedJasmine\Address\Enums\AddressValidateLevel;
use RedJasmine\Address\Exceptions\AddressException;
use RedJasmine\Region\Enums\RegionLevel;
use RedJasmine\Region\Facades\Region;
use RedJasmine\Support\Contracts\User;

class Address
{
    // Build wonderful things


    /**
     * 创建地址
     * @param User $owner
     * @param array $data
     * @param User $creator
     * @param AddressValidateLevel $validateLevel
     * @return Models\Address
     */
    public function createForID(User $owner, array $data, User $creator, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT,) : Models\Address
    {

        $data['is_default'] = (int)(boolean)(int)($data['is_default'] ?? 0);
        $data['sort']       = (int)($data['sort'] ?? 0);
        // 基础信息验证
        $data = $this->validator($data);
        // 验证省份
        $data                  = $this->regionValidate($data, $validateLevel);
        $address               = new Models\Address();
        $address->owner_type   = $owner->getUserType();
        $address->owner_uid    = $owner->getUID();
        $address->creator_type = $creator->getUserType();
        $address->creator_uid  = $creator->getUID();
        $address->fill($data);
        $address->save();
        return $address;
    }

    /**
     * 更新地址
     * @param int $id
     * @param array $data
     * @param User $updater
     * @param AddressValidateLevel $validateLevel
     * @return Models\Address
     */
    public function updateForID(int $id, array $data, User $updater, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT) : Models\Address
    {
        $data['is_default'] = (int)(boolean)(int)($data['is_default'] ?? 0);
        $data['sort']       = (int)($data['sort'] ?? 0);
        // 基础信息验证
        $data = $this->validator($data);
        // 验证省份
        $data    = $this->regionValidate($data, $validateLevel);
        $address = Models\Address::findOrFail($id);

        $address->updater_type = $updater->getUserType();
        $address->updater_uid  = $updater->getUID();
        $address->fill($data);
        $address->save();
        return $address;
    }


    /**
     * 验证区域
     * @param array $data
     * @param AddressValidateLevel $validateLevel
     * @return array
     * @throws AddressException
     */
    public function regionValidate(array $data, AddressValidateLevel $validateLevel = AddressValidateLevel::DISTRICT) : array
    {

        $regionRules = [
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
        $id          = [];
        foreach ($regionRules as $rule) {
            if ($data[$rule['field']] ?? null) {
                $id[] = (int)$data[$rule['field']];
            }
        }

        $regions = Region::query($id)->keyBy('id')->all();
        foreach ($regionRules as $rule) {
            // ID 存在必须验证
            if (filled($data[$rule['field']] ?? null) || $validateLevel->value >= $rule['validate_level']->value) {
                $region = $regions[(int)$data[$rule['field']]] ?? null;
                if (blank($region)) {
                    throw new AddressException("{$rule['name']}不存在");
                }
                // 验证等级
                if ($region->level !== $rule['level']) {
                    throw new AddressException("{$rule['name']} 等级不一致");
                }
                // 验证关系
                if (filled($rule['parent_id_field'] ?? null) && $region->parent_id !== (int)$data[$rule['parent_id_field']]) {
                    throw new AddressException("{$rule['name']} 关系不一致");
                }
                $data[Str::replace('_id', '', $rule['field'])] = $region->name;
            } else {
                $data[$rule['field']]                          = null;
                $data[Str::replace('_id', '', $rule['field'])] = '';
            }
        }


        return $data;
    }

    /**
     * 基础验证
     * @param array $data
     * @return array
     */
    public function validator(array $data = []) : array
    {
        $rules      = [
            'contacts' => [ 'required', 'max:30', ],
            'mobile'   => [ 'required', 'max:20', ],
            'address'  => [ 'sometimes', 'max:100', ],
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
}
