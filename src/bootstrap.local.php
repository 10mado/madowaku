<?php
$app = new App\Silex\Application();

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

$app['debug'] = true;
$app['config'] = [
    'asset.files' => require __DIR__ . '/asset_files.php',
];

$app->register(new Silex\Provider\SessionServiceProvider());
$app['session.storage.options'] = [
    'name' => App\Constants::SESSION_NAME,
    'cookie_lifetime' => App\Constants::SESSION_LIFETIME,
    'cookie_httponly' => 1,
    // 'cookie_secure' => 1,
];

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
    'twig.options' => [
        'debug' => true,
    ]
]);
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    App\Silex\Util\TwigExtension::extend($twig, $app);
    return $twig;
});

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => 'php://stderr',
    'monolog.level' => Monolog\Logger::DEBUG,
    'monolog.listener' => function () use ($app) {
        return new App\Silex\EventListener\LogListener($app['logger']);
    },
]);

$app['params'] = function () use ($app) {
    return new Silexcane\Silex\Service\Params($app);
};

$app['messages'] = function () use ($app) {
   return new Silexcane\Silex\Service\Messages($app);
};

$app['csrf_token'] = function () use ($app) {
    return new Silexcane\Silex\Service\CsrfToken($app);
};

return $app;
