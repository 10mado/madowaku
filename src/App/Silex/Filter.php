<?php
namespace App\Silex;

class Filter
{
    public static function preExecute(Application $app)
    {
/*
        if ($app['session']->has('user_id')) {
            $userId = $app['session']->get('user_id');
            $user = $app['m.users']->findById($userId);
            if ($user) {
                // 各種値設定
                $app['is_login'] = true;
                $app['self_user'] = $user;
            }
        }
*/
    }
}
