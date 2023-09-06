<?php

namespace RedJasmine\Region;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Region\Enums\RegionLevel;
use RedJasmine\Region\Models\Region as RegionModel;

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
     * @param array|int|string $id
     *
     * @return  RegionModel[]|Collection|array
     */
    public function query(array|int|string $id) : array|Collection
    {
        if (is_array($id)) {
            $id = array_filter($id);
        } else {
            $id = [ (int)$id ];
        }
        if (blank($id)) {
            return [];
        }
        return RegionModel::whereIn('id', $id)->get();
    }

    /**
     * 省份
     * @return RegionModel[]|Collection
     */
    public function provinces() : array|Collection
    {
        return RegionModel::select($this->fields)
                          ->where('parent_id', 0)
                          ->where('level', RegionLevel::PROVINCE->value)
                          ->get();
    }

    /**
     * 查询子集
     *
     * @param int $parentID
     *
     * @return RegionModel[]|Collection
     */
    public function children(int $parentID) : array|Collection
    {
        return RegionModel::select($this->fields)->where('parent_id', (int)$parentID)->get();
    }

    /**
     *
     * @param int|string $province
     *
     * @return Models\Region[]|array|Collection
     */
    public function cities(int|string $province) : Collection|array
    {
        $query = RegionModel::query();
        $query->select($this->fields);
        if (is_numeric($province)) {
            return $this->children($province);
        } else {
            $name = $province;
            return $query->where('name', (string)$name)->get();
        }

    }


    public function tree(int $level = RegionLevel::DISTRICT->value) : array
    {
        $regionLevel = RegionLevel::tryFrom($level);

        $query = RegionModel::query();

        $regions = $query
            ->select($this->fields)
            ->where('level', '<=', $regionLevel->value)
            ->get()->toArray();
        return $this->buildTree($regions);
    }


    protected function buildTree(array $array, $parentId = 0) : array
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
