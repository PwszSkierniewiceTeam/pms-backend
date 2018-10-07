<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';

$c = new \Slim\Container(); //Create Your container

//Override the default error handlers
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $data = array('data' => 404);
        return $c['response']
            ->withStatus(404)
            ->withJson($data);
    };
};

$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        $data = array('data' => 405);
        return $c['response']
            ->withStatus(405)
            ->withJson($data);
    };
};

$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        $data = array('data' => 500);
        return $c['response']
            ->withStatus(500)
            ->withJson($data);
    };
};

// Create an app
$app = new \Slim\App($c);

// Setup CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Example route
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});


// Run application
$app->run();