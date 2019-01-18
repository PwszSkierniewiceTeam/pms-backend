<?php

use PMS\Controllers\Tasks\CreateTaskController;
use PMS\Controllers\Tasks\DeleteTaskController;
use PMS\Controllers\Tasks\GetTaskController;
use PMS\Controllers\Tasks\ListTasksController;
use PMS\Controllers\Tasks\UpdateTaskController;
use PMS\Controllers\Project\RemoveProjectController;
use PMS\Controllers\Project\GetAllProjectsController;
use PMS\Controllers\Project\GetProjectController;
use PMS\Controllers\Project\PostNewProjectController;
use PMS\Controllers\Project\UpdateProjectController;
use PMS\Controllers\Project\GetProjectUsersController;
use PMS\Controllers\Project\AssignUserToProjectController;
use PMS\Controllers\Project\RemoveUserFromProjectController;
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
 * GET getProject
 * Summary: get project by id
 */
$app->get('/projects/{projectId}', function (Request $request, Response $response, array $args) {
    $controller = new GetProjectController($this->db);
    return $controller->handleRequest($request, $response, $args);
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
 * POST assignUserToProject
 * Summary: Assign user to the project
 */
$app->post('/projects/{projectId}/users', function (Request $request, Response $response, array $args) {
    $controller = new AssignUserToProjectController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * DELETE removeUserFromProject
 * Summary: Delete user from the project
 */
$app->delete('/projects/{projectId}/users/{userId}', function (Request $request, Response $response, array $args) {
    $controller = new RemoveUserFromProjectController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * POST createTask
 * Summary: Create new task in the project
 */
$app->post('/tasks', function (Request $request, Response $response) {
    $controller = new CreateTaskController($this->db);
    return $controller->handleRequest($request, $response);
});

/**
 * GET getTasks
 * Summary: Get list of the tasks assigned to the project
 */
$app->get('/tasks', function (Request $request, Response $response) {
    $controller = new ListTasksController($this->db);
    return $controller->handleRequest($request, $response);
});


/**
 * GET getTask
 * Summary: Get task
 */
$app->get('/tasks/{taskId}', function (Request $request, Response $response, array $args) {
    $controller = new GetTaskController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * DELETE removeTask
 * Summary: Delete the task
 */
$app->delete('/tasks/{taskId}', function (Request $request, Response $response, array $args) {
    $controller = new DeleteTaskController($this->db);
    return $controller->handleRequest($request, $response, $args);
});

/**
 * PUT updateTask
 * Summary: Update task
 */
$app->put('/tasks/{taskId}', function (Request $request, Response $response, array $args) {
    $controller = new UpdateTaskController($this->db);
    return $controller->handleRequest($request, $response, $args);
});
