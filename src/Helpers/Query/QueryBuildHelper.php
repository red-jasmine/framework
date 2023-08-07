<?php

namespace RedJasmine\Support\Helpers\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class QueryBuildHelper
{


    public static function explode($value, string $valueType = 'int')
    {
        $list = [];
        if (is_array($value)) {
            $list = $value;
        } else {
            $value = (string)$value;
            $list  = explode(',', $value);
        }
        if (blank($list)) {
            return $list;
        }
        $list      = collect($list)->filter(function ($item) {
            return filled($item);
        });
        $valueType .= 'val';
        $list      = $list->map(function ($item) use ($valueType) {
            return ($valueType)($item);
        });
        return $list->toArray();
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param array $fields
     * @param array $conditions
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function ranges($query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            $query = self::range($query, $field, $conditions[$field] ?? '', $type);
        }
        return $query;
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param $field
     * @param $condition
     * @param string $valueType
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function range($query, $field, $condition,string $valueType = 'int')
    {
        if (blank($condition)) {
            return $query;
        }
        $list = [];
        if (is_array($condition)) {
            $list = $condition;
        } else {
            $condition = (string)$condition;
            $list      = explode(',', $condition);
        }

        $list  = collect($list)->map(function ($item) use ($valueType) {
            if (filled($item)) {
                // 格式格式化数据
                // 如果是时间 datetime // todo
                if ($valueType === 'datetime') {
                    return $item;
                } else {
                    return ($valueType . 'val')($item);
                }
            } else {
                return null;
            }
        });
        $start = $list[0] ?? null;
        $end   = $list[1] ?? null;
        if (filled($start)) {
            $query = $query->where($field, '>=', $start);
        }
        if (filled($end)) {
            $query = $query->where($field, '<', $end);
        }
        return $query;
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param array $fields
     * @param array $conditions
     * @return Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public static function arrays($query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            $query = self::array($query, $field, $conditions[$field] ?? '', $type);
        }
        return $query;
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param $field
     * @param $condition
     * @param string $valueType
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function array($query, $field, $condition, string $valueType = 'int')
    {
        if (blank($condition)) {
            return $query;
        }
        $list = [];
        if (is_array($condition)) {
            $list = $condition;
        } else {
            $condition = (string)$condition;
            $list      = explode(',', $condition);
        }
        if (blank($list)) {
            return $query;
        }
        $list      = collect($list)->filter(function ($item) {
            return filled($item);
        });
        $valueType .= 'val';
        $list      = $list->map(function ($item) use ($valueType) {
            return ($valueType)($item);
        });
        $list      = $list->values()->toArray();
        if (count($list) < 1) {
            return $query;
        }
        if (count($list) > 1) {
            $query = $query->whereIn($field, $list);
        }
        if (count($list) === 1) {
            $query = $query->where($field, $list[0]);
        }
        return $query;
    }

    /**
     * 等于
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param array $fields
     * @param array $conditions
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function equals($query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            if (filled($conditions[$field] ?? null)) {

                $query = $query->where($field, ($type . 'val')($conditions[$field]));
            }
        }
        return $query;
    }


    /**
     * 时间查询
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param $field
     * @param $condition
     * @param string $timeType
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function times($query, $field, $condition, string $timeType = 'd')
    {

        try {
            if (blank($condition)) {
                return $query;
            }
            if (is_array($condition)) {
                $list = $condition;
            }
            if (is_string($condition)) {
                $list = explode(',', $condition);
            }
            $start = $list[0] ?? $list['start'] ?? null;
            $end   = $list[1] ?? $list['end'] ?? null;
            // 存在开始时间
            if (filled($start)) {
                $start = Carbon::parse($start);
                ($timeType === 'd') ? $start->startOfDay() : ''; //日
                ($timeType === 'm') ? $start->startOfMonth() : ''; // 月
                $start = $start->toDateTimeString();
                $query = $query->where($field, '>=', $start);
            }
            if (filled($end)) {
                $end = Carbon::parse($end);
                ($timeType === 'd') ? $end->endOfDay() : '';
                ($timeType === 'm') ? $end->endOfMonth() : '';
                $end   = $end->toDateTimeString();
                $query = $query->where($field, '<=', $end);
            }
        } catch (\Throwable $throwable) {
            return $query;
        }
        return $query;


    }

    /**
     * 排序 多字段
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param array $sorts
     * @param string $sort
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function orderBy($query, array $sorts, string $sort = '')
    {
        $orderBuys = $sorts[$sort]['sorts'] ?? $sorts[$sort] ?? [];

        foreach ($orderBuys as $field => $type) {
            $query = $query->orderBy($field, $type);
        }
        return $query;
    }

}
