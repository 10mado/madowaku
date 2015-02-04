<?php
namespace App\Silex\Util;

use Silexcane\Silex\Application;

class TwigExtension extends \Silexcane\Silex\Util\TwigExtension
{
    public static function extend(\Twig_Environment $twig, Application $app)
    {
        parent::extend($twig, $app);

        $twig->addFunction(new \Twig_SimpleFunction('asset', function ($assetPath, $isPathOnly = false) use ($app) {
            $assetBase = '';
            if (!$isPathOnly && isset($app['config']['asset.base'])) {
                $assetBase = rtrim($app['config']['asset.base'], '/');
            }
            if (array_key_exists($assetPath, $app['config']['asset.files'])) {
                return $assetBase . $app['config']['asset.files'][$assetPath];
            }
            return '';
        }));

        $twig->addFunction(new \Twig_SimpleFunction('page_query', function (array $queries, $page = 1) {
            if (is_int($page) && $page > 1) {
                $queries = array_merge($queries, ['page' => $page]);
            }
            $query = http_build_query($queries);
            return strlen($query) > 0 ? "?{$query}" : '';
        }));

        $twig->addExtension(new \Twig_Extensions_Extension_Text());
    }
}
