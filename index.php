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

// Example route
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

// Run application
$app->run();
