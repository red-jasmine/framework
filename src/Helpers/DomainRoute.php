<?php

namespace RedJasmine\Support\Helpers;


use Illuminate\Support\Facades\URL;

class DomainRoute
{

    public static bool $subDomain = false;

    public static function boot() : void
    {
        // 是否开启子域名
        self::$subDomain = config('app.sub_domain', false);

        if (self::$subDomain) {
            $host    = app('request')->getHost();
            $domains = explode('.', $host);
            $domains = array_reverse($domains);
            $tld     = $domains[0] ?? '';
            $sld     = $domains[1] ?? '';
            URL::defaults([ 'sld' => $sld, 'tld' => $tld, ]);
        }
    }

    /**
     * 设置域名
     * @param string|null $domain
     * @param bool $isApi
     * @return string|null
     */
    public static function domain(string $domain = null, bool $isApi = false) : ?string
    {
        // 是否开启子域名
        self::$subDomain = config('app.sub_domain', false);
        if (self::$subDomain) {
            if (filled($domain)) {
                $domain = $isApi ? ('api.' . $domain) : $domain;
                return $domain . '.{sld}.{tld}';
            } else {
                return '{sld}.{tld}';
            }
        } else {
            return null;
        }
    }


    /**
     * 路由前缀
     * @param string $module
     * @param string|null $guard
     * @param bool $isApi
     * @return string
     */
    public static function prefix(string $module, string $guard = null, bool $isApi = false) : string
    {
        $prefix = $isApi ? 'api/' : '';
        $prefix .= $guard;
        return $prefix . '/' . $module;
    }


    public static function adminWebPrefix(string $module = null) : string
    {
        if (filled($module)) {
            return config('admin.route.prefix') . '/' . $module;
        }
        return config('admin.route.prefix');
    }

    public static function userApiPrefix(string $module) : string
    {
        return self::prefix($module, 'user', true);
    }

    public static function shopApiPrefix(string $module) : string
    {
        return self::prefix($module, 'shop', true);
    }

}
