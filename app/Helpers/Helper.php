<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('vdQuery')) {
    /**
     * get database exec query and data
     */
    function vdQuery(Closure $callBack)
    {
        DB::enableQueryLog();
        $res = $callBack();
        var_dump(DB::getQueryLog());
        return $res;
    }
}

if (!function_exists('ddQuery')) {
    /**
     * get database exec query and data
     */
    function ddQuery(Closure $callBack)
    {
        DB::enableQueryLog();
        $callBack();
        dd(DB::getQueryLog());
    }
}


if (!function_exists('swagger_select_definition')) {
    /**
     * get config/swagger file name
     *
     * @return string
     */
    function swagger_select_definition()
    {
        $path = storage_path() . '/api-docs';

        $key = key(config('l5-swagger.documentations'));

        $urls = [];

        if (config('l5-swagger.swaggerExample')) {
            $urls[] = [
                'url' => sprintf("/docs/%s-docs.json", $key),
                'name' => $key
            ];
        }

        if (is_dir($path)) {
            $swaggerPath = scandir($path);

            collect($swaggerPath)->map(function ($item) use (&$urls) {
                if (preg_match('/[a-zA-Z]/i', $item)) {
                    if (preg_match('/json/i', $item) || preg_match('/yaml/i', $item))
                        $key = substr($item, 0, -5);
                    elseif (preg_match('/yml/i', $item))
                        $key = substr($item, 0, -4);
                    else
                        throw new Exception('please check swagger file extension. must be type json, yml, yaml');

                    if (config('l5-swagger.swaggerExample') || preg_match('/example/i', $item))
                        return;

                    if ($searchKey = array_search(["url" => "/docs/$key.json", "name" => $key], $urls))
                        unset($urls[$searchKey]);

                    if ($searchKey = array_search(["url" => "/docs/$key.yaml", "name" => $key], $urls))
                        unset($urls[$searchKey]);

                    $urls[] = [
                        "url" => "/docs/$item",
                        "name" => $key
                    ];
                }
            });
        }

        return sprintf("urls: %s,", json_encode(array_values($urls)));
    }
}

if (!function_exists('ve')) {
    /**
     * var_export
     */
    function ve($item, $die = true)
    {
        var_export($item);
        echo "\r\n";
        if ($die)
            die;
    }
}

if (!function_exists('veQuery')) {
    /**
     * get database exec query and data
     */
    function veQuery(Closure $callBack, $die = true)
    {
        DB::enableQueryLog();
        $callBack();
        ve(DB::getQueryLog(), $die);
    }
}
