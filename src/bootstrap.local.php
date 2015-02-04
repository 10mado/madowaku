<?php
$app = new App\Silex\Application();

Dotenv::load(__DIR__ . '/../');

$app['debug'] = true;
$app['config'] = [
    'asset.files' => require __DIR__ . '/asset_files.php',
];

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

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
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    App\Silex\Util\TwigExtension::extend($twig, $app);
    return $twig;
}));

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => 'php://stderr',
    'monolog.level' => Monolog\Logger::DEBUG,
    'monolog.listener' => $app->share(function () use ($app) {
        return new App\Silex\EventListener\LogListener($app['logger']);
    }),
]);

$app['params'] = $app->share(function () use ($app) {
    return new Silexcane\Silex\Service\Params($app);
});

$app['messages'] = $app->share(function () use ($app) {
   return new Silexcane\Silex\Service\Messages($app);
});

$app['security_token_validator'] = $app->share(function () use ($app) {
    return new Silexcane\Silex\Service\SecurityTokenValidator($app);
});

return $app;
