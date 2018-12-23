<?php

use PMS\Controllers\Tasks\CreateTaskController;
use PMS\Controllers\Tasks\DeleteTaskController;
use PMS\Controllers\Tasks\ListTasksController;
use PMS\Controllers\Tasks\UpdateTaskController;
use PMS\Controllers\User\AuthorizationController;
use PMS\Controllers\User\RegisterController;
use Slim\Http\Request;
use Slim\Http\Response;

// Authenticate route.
$app->post('/users/login', function (Request $request, Response $response) {
    $controller = new AuthorizationController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * POST register
 * Summary: Registers the user
 * Notes: Creates new user entry in the system.
 */
$app->post('/users/register', function (Request $request, Response $response) {
    $controller = new RegisterController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * POST createProject
 * Summary: Create new project
 */
$app->post('/projects', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * GET getProjects
 * Summary: Get list of the projects viewable for the current user
 */
$app->get('/projects', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * DELETE removeProject
 * Summary: Delete the project
 */
$app->delete('/projects/{projectId}', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * PUT updateProject
 * Summary: Update the project
 */
$app->put('/projects/{projectId}', function (Request $request, Response $response) {
    $body = $request->getParsedBody();

    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * POST createTask
 * Summary: Create new task in the project
 */
$app->post('/tasks', function (Request $request, Response $response) {
//    $queryParams = $request->getQueryParams();
//    $projectId = $queryParams['projectId'];
//    $body = $request->getParsedBody();
    $controller = new CreateTaskController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * GET getTasks
 * Summary: Get list of the tasks assigned to the project
 */
$app->get('/tasks', function (Request $request, Response $response) {
//    $queryParams = $request->getQueryParams();
//    $projectId = $queryParams['projectId'];

    $controller = new ListTasksController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * DELETE removeTask
 * Summary: Delete the task
 */
$app->delete('/tasks/{taskId}', function (Request $request, Response $response, $args) {
    $controller = new DeleteTaskController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * PUT updateTask
 * Summary: Update task
 */
$app->put('/tasks/{taskId}', function (Request $request, Response $response, $args) {
    $controller = new UpdateTaskController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

$app->delete('/projects/{projectId}/users/{userId}', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});

$app->post('/projects/{projectId}/users', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});