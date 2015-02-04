<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    // for Heroku environmemt
    $_SERVER['HTTPS'] = 'on';
}

Symfony\Component\HttpFoundation\Request::enableHttpMethodParameterOverride();

$app = null;
switch (getenv('PHP_ENV')) {
    default:
        $app = require __DIR__ . '/bootstrap.local.php';
        break;
}

$app['env'] = getenv('PHP_ENV');
$app['is_login'] = false;
$app['self_user'] = null;

// models?

$app->error(function (\Exception $e, $code) use ($app) {
    App\Silex\Filter::preExecute($app);
    if ($e instanceof Silexcane\Silex\Exception\MustLoginException) {
        $app['messages']->add('ログインしてください。');
        return $app->redirect($app->url('top'));
    } elseif ($e instanceof Silexcane\Silex\Exception\MustLogoutException) {
        return $app->redirect($app->url('top'));
    }
    switch ($code) {
        case 404:
            return $app->render('error_404.html');
        default:
            return $app->render('error_default.html');
    }
});

$app->before(function () use ($app) {
    App\Silex\Filter::preExecute($app);
    $app['twig']->addGlobal('messages', $app['messages']->all());
    $app['twig']->addGlobal('errors', []);
    $app['twig']->addGlobal('security_token_name',
            Silexcane\Silex\Service\SecurityTokenValidator::SECURITY_TOKEN_ITEM_NAME);
});

$router = new App\Silex\Router($app);
// 本番環境などはSSLが望ましい
// if () {
//     $router->forceHttps();
// }
$router->route();

return $app;
