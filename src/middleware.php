<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$jwt = $app->getContainer()['settings']['jwt'];

$app->add(new Tuupola\Middleware\JwtAuthentication([
    'secure' => $jwt['secure'],
    'secret' => $jwt['secret'],
    'path' => ['/'],
    'ignore' => ['/users/login', '/users/register'],
    'algorithm' => $jwt['algorithm']
]));

// A middleware for enabling CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
