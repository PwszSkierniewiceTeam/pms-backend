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

/**
 * POST createProject
 * Summary: Create new project
 */
$app->post('/projects', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $response->write('How about implementing createProject as a POST method ?');
    return $response;
});

/**
 * GET getProjects
 * Summary: Get list of the projects viewable for the current user
 */
$app->get('/projects', function ($request, $response, $args) {
    $response->write('How about implementing getProjects as a GET method ?');
    return $response;
});

/**
 * DELETE removeProject
 * Summary: Delete the project
 */
$app->delete('/projects/{projectId}', function ($request, $response, $args) {
    $response->write('How about implementing removeProject as a DELETE method ?');
    return $response;
});

/**
 * PUT updateProject
 * Summary: Update the project
 */
$app->put('/projects/{projectId}', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $response->write('How about implementing updateProject as a PUT method ?');
    return $response;
});

/**
 * POST createTask
 * Summary: Create new task in the project
 */
$app->post('/tasks', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $projectId = $queryParams['projectId'];
    $body = $request->getParsedBody();
    $response->write('How about implementing createTask as a POST method ?');
    return $response;
});

/**
 * GET getTasks
 * Summary: Get list of the tasks assigned to the project
 */
$app->get('/tasks', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $projectId = $queryParams['projectId'];
    $response->write('How about implementing getTasks as a GET method ?');
    return $response;
});

/**
 * DELETE removeTask
 * Summary: Delete the task
 */
$app->delete('/tasks/{taskId}', function ($request, $response, $args) {
    $response->write('How about implementing removeTask as a DELETE method ?');
    return $response;
});

/**
 * PUT updateTask
 * Summary: Update task
 */
$app->put('/tasks/{taskId}', function ($request, $response, $args) {
    $response->write('How about implementing updateTask as a PUT method ?');
    return $response;
});

/**
 * POST login
 * Summary: Login user into the system
 * Notes: Creates a new authorization token.
 */
$app->post('/users/login', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $response->write('How about implementing login as a POST method ?');
    return $response;
});

/**
 * POST register
 * Summary: Registers the user
 * Notes: Creates new user entry in the system.
 */
$app->post('/users/register', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $response->write('How about implementing register as a POST method ?');
    return $response;
});

// Run application
$app->run();
