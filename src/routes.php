<?php

use PMS\Controllers\Project\RemoveProjectController;
use PMS\Controllers\Project\GetAllProjectsController;
use PMS\Controllers\Project\PostNewProjectController;
use PMS\Controllers\Project\UpdateProjectController;
use PMS\Controllers\Project\GetProjectUsersController;
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
    $controller = new PostNewProjectController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * GET getProjects
 * Summary: Get list of the projects viewable for the current user
 */
$app->get('/projects', function (Request $request, Response $response) {
    $controller = new GetAllProjectsController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * DELETE removeProject
 * Summary: Delete the project
 */
$app->delete('/projects/{projectId}', function (Request $request, Response $response, array $args) {
    $controller = new RemoveProjectController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * PUT updateProject
 * Summary: Update the project
 */
$app->put('/projects/{projectId}', function (Request $request, Response $response, array $args) {
    $controller = new UpdateProjectController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * GET getProjectUsers
 * Summary: Get list of users assigned to the project
 */
$app->get('/projects/{projectId}/users', function (Request $request, Response $response, array $args) {
    $controller = new GetProjectUsersController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * POST createTask
 * Summary: Create new task in the project
 */
$app->post('/tasks', function (Request $request, Response $response) {
    $queryParams = $request->getQueryParams();
    $projectId = $queryParams['projectId'];
    $body = $request->getParsedBody();

    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * GET getTasks
 * Summary: Get list of the tasks assigned to the project
 */
$app->get('/tasks', function (Request $request, Response $response) {
    $queryParams = $request->getQueryParams();
    $projectId = $queryParams['projectId'];

    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * DELETE removeTask
 * Summary: Delete the task
 */
$app->delete('/tasks/{taskId}', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});

/**
 * PUT updateTask
 * Summary: Update task
 */
$app->put('/tasks/{taskId}', function (Request $request, Response $response) {
    return $response->withJson(['data' => 'Please implement this method']);
});
