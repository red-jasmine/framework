<?php

namespace RedJasmine\Region\Application\Services\Country;

use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Region\Application\Services\Country\Queries\CountryPaginateQuery;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;

/**
 * 国家服务 - 基于 Symfony Intl 组件
 */
class CountryService
{
    /**
     * 获取所有国家列表
     *
     * @param string $locale 语言代码，默认 zh_CN
     * @return array
     */
    public function all(string $locale = 'zh_CN'): array
    {
        $countries = [];
        $codes = Countries::getNames($locale);
        foreach ($codes as $code=> $name) {

            $countries[] = [
                'code'=>$code,
                'name'=>$name,
            ];
        }

        return $countries;
    }

    /**
     * 根据代码查找国家
     *
     * @param string $code ISO 3166-1 alpha-2 代码
     * @param string $locale 语言代码
     * @return array|null
     */
    public function find(string $code, string $locale = 'zh_CN'): ?array
    {
        try {
            return $this->getCountryData($code, $locale);
        } catch (MissingResourceException $e) {
            return null;
        }
    }

    /**
     * 分页查询国家列表
     *
     * @param CountryPaginateQuery $query
     * @param string $locale
     * @return LengthAwarePaginator
     */
    public function paginate(CountryPaginateQuery $query, string $locale = 'zh_CN'): LengthAwarePaginator
    {
        $countries = $this->all($locale);

        // 应用过滤条件
        $filtered = $this->applyFilters($countries, $query);

        // 获取分页参数
        $page = $query->page ?? 1;
        $perPage = $query->pageSize ?? 15;

        // 计算分页
        $total = count($filtered);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($filtered, $offset, $perPage);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * 应用过滤条件
     *
     * @param array $countries
     * @param CountryPaginateQuery $query
     * @return array
     */
    protected function applyFilters(array $countries, CountryPaginateQuery $query): array
    {
        return array_filter($countries, function ($country) use ($query) {
            // 按代码过滤
            if ($query->code && stripos($country['code'], $query->code) === false) {
                return false;
            }

            // 按 ISO Alpha-3 过滤
            if ($query->isoAlpha3 && stripos($country['iso_alpha_3'], $query->isoAlpha3) === false) {
                return false;
            }

            // 按名称过滤
            if ($query->name && stripos($country['name'], $query->name) === false) {
                return false;
            }

            return true;
        });
    }

    /**
     * 获取国家数据
     *
     * @param string $code
     * @param string $locale
     * @return array
     */
    protected function getCountryData(string $code, string $locale = 'zh_CN'): array
    {
        $upperCode = strtoupper($code);

        return [
            'code' => $upperCode,
            'iso_alpha_3' => Countries::getAlpha3Code($upperCode),
            'name' => Countries::getName($upperCode, $locale),
            'native' => Countries::getName($upperCode, $this->getCountryLocale($upperCode)),
        ];
    }

    /**
     * 获取国家的本地语言代码
     *
     * @param string $code
     * @return string
     */
    protected function getCountryLocale(string $code): string
    {
        // 这里可以根据国家代码返回对应的本地语言
        // 简单处理，部分常见国家
        $localeMap = [
            'CN' => 'zh_CN',
            'US' => 'en_US',
            'GB' => 'en_GB',
            'JP' => 'ja_JP',
            'KR' => 'ko_KR',
            'FR' => 'fr_FR',
            'DE' => 'de_DE',
            'ES' => 'es_ES',
            'IT' => 'it_IT',
            'RU' => 'ru_RU',
        ];

        return $localeMap[$code] ?? 'en_US';
    }

    /**
     * 检查国家代码是否存在
     *
     * @param string $code
     * @return bool
     */
    public function exists(string $code): bool
    {
        return Countries::exists(strtoupper($code));
    }

    /**
     * 获取国家名称
     *
     * @param string $code
     * @param string $locale
     * @return string|null
     */
    public function getName(string $code, string $locale = 'zh_CN'): ?string
    {
        try {
            return Countries::getName(strtoupper($code), $locale);
        } catch (MissingResourceException $e) {
            return null;
        }
    }
}

