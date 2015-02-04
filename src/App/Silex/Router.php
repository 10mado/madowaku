<?php
namespace App\Silex;

class Router extends \Silexcane\Silex\Router
{
    public function route()
    {
        $this->routeGet('/', 'TopController::getAction', 'top');
    }
}
