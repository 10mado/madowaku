<?php
namespace App\Controller;

use App\Silex\Application;
use App\Silex\Controller;
use Symfony\Component\HttpFoundation\Request;

class TopController extends Controller
{
    public function getAction(Request $req, Application $app)
    {
        return $app->render('top.html');
    }
}
