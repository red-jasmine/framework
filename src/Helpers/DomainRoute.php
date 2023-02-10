<?php

namespace RedJasmine\Support\Helpers;


use Illuminate\Support\Facades\URL;

class DomainRoute
{

    public static bool $subDomain = false;

    public static function boot() : void
    {
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
     * 域名
     * @param string|null $domain
     * @return null|string
     */
    public static function domain(string $domain = null) : ?string
    {
        self::$subDomain = config('app.sub_domain', false);
        if (self::$subDomain) {
            if (filled($domain)) {
                return $domain . '.{sld}.{tld}';
            } else {
                return '{sld}.{tld}';
            }
        } else {
            return null;
        }
    }

}
