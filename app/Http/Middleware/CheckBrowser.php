<?php

namespace App\Http\Middleware;

use BrowscapPHP\Browscap;
use Closure;

class CheckBrowser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $f = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env');
        $f = explode("\n", $f);
        $flag = false;
        foreach ($f as $item) {
            if (stristr($item, 'BLOCK_')) {
                if (stristr($item, 'true')) {
                    $flag = true;
                    break;
                }
            }
        }
        if ($flag) {
            $cacheDir = config('browscap.cache');
            $fileCache = new \Doctrine\Common\Cache\FilesystemCache($cacheDir);
            $cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($fileCache);

            $logger = new \Monolog\Logger('name');

            $bc = new Browscap($cache, $logger);
            $result = $bc->getBrowser();
            $arr_browsers[] = strtoupper($result->browser);
            if ($result->ismobiledevice == true) {
                $arr_browsers[] = 'MOBILE';
            }
            if ($result->ismobiledevice == true) {
                $arr_browsers[] = 'TAB';
            }
            foreach ($arr_browsers as $item) {
                if (env('BLOCK_' . $item) == true) {
                    return redirect()->route('abortBrowser');
                }
            }
        }
        return $next($request);
    }
}
