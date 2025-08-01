<?php

namespace RedJasmine\Region;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Region\Domain\Enums\RegionTypeEnum;
use RedJasmine\Region\Domain\Models\Region as RegionModel;
use RedJasmine\Region\Enums\RegionLevel;

class Region
{
    // Build wonderful things


    protected array $fields = [
        'id', 'parent_id', 'name', 'pinyin', 'pinyin_prefix', 'level'
    ];

    public function fileds(array $fields = null)
    {
        if (filled($fields)) {
            $this->fields = $fields;
        }
        return $this;
    }

    public function find(int $id) : RegionModel
    {
        return RegionModel::find($id);
    }


    /**
     * 查询区划数据
     *
     * @param  array|int|string  $id
     *
     * @return  RegionModel[]|Collection|array
     */
    public function query(array|int|string $id) : array|Collection
    {
        if (is_array($id)) {
            $id = array_filter($id);
        } else {
            $id = [(int) $id];
        }
        if (blank($id)) {
            return [];
        }
        return RegionModel::whereIn('id', $id)->get();
    }


    /**
     * 国家
     * @return RegionModel[]|Collection
     */
    public function countries() : array|Collection
    {
        return RegionModel::select($this->fields)
                          ->where('parent_id', 0)
                          ->where('level', RegionTypeEnum::COUNTRY->value)
                          ->get();
    }

    /**
     * 省份
     * @return RegionModel[]|Collection
     */
    public function provinces(int $parentID = 1) : array|Collection
    {
        return RegionModel::select($this->fields)
                          ->where('parent_id', $parentID)
                          ->where('level', RegionTypeEnum::PROVINCE->value)
                          ->get();
    }

    /**
     * 查询子集
     *
     * @param  int  $parentID
     *
     * @return RegionModel[]|Collection
     */
    public function children(int $parentID) : array|Collection
    {
        return RegionModel::select($this->fields)->where('parent_id', (int) $parentID)->get();
    }

    /**
     *
     * @param  int|string  $province
     *
     * @return RegionModel[]|array|Collection
     */
    public function cities(int|string $province) : Collection|array
    {
        $query = RegionModel::query();
        $query->select($this->fields);
        if (is_numeric($province)) {
            return $this->children($province);
        } else {
            $name = $province;
            return $query->where('name', (string) $name)->get();
        }

    }


    public function tree(RegionTypeEnum $level = RegionTypeEnum::DISTRICT) : array
    {
        $regionLevel = RegionTypeEnum::tryFrom($level);

        $query   = RegionModel::query();
        $regions = $query
            ->select($this->fields)
            ->where('level', '<=', $regionLevel->value)
            ->get()->toArray();
        return $this->buildTree($regions);
    }


    protected function buildTree(array $array, $parentId = 1) : array
    {
        $tree = array ();
        foreach ($array as $item) {
            if ($item['parent_id'] === $parentId) {
                $children = $this->buildTree($array, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }
}
