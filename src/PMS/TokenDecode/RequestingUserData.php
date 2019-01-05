<?php
/**
 * Created by PhpStorm.
 * User: Grimneon
 * Date: 11.11.2018
 * Time: 22:03
 */

namespace PMS\TokenDecode;


use Firebase\JWT\JWT;
use Slim\Http\Request;

class RequestingUserData
{
    static public function getUserId(Request $request)
    {
        global $app;
        $secret = $app->getContainer()['settings']['jwt']['secret'];
        $algorithm = $app->getContainer()['settings']['jwt']['algorithm'];
        $jwt = $request->getHeaders()['HTTP_AUTHORIZATION'][0];
        $key = explode(' ', $jwt)[1];
        return get_object_vars(JWT::decode($key, $secret, $algorithm)->user)["id"];
    }
}