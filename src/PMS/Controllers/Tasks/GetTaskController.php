<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 05.01.2019
 * Time: 12:57
 */

namespace PMS\Controllers\Tasks;


use PMS\Controllers\BaseController;
use PMS\Queries\CommonQueries;
use PMS\TokenDecode\RequestingUserData;
use Slim\Http\Request;
use Slim\Http\Response;

class GetTaskController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {

        $userId = RequestingUserData::getUserId($request);
        $taskId = $args['taskId'];


        $projectId = CommonQueries::findProjectIdByTaskId($this->db, $taskId);


        if(!(CommonQueries::findUserRole($this->db, $projectId, $userId ))) {
            return $response->withJson(["uncategorized" => "User is not assigned to this project"], 404);
        }

        $task = CommonQueries::findTaskById($this->db, $taskId);
        if (!$task) {
            return $response->withJson(["uncategorized" => "Task doesn't exist"], 401);
        }


        return $response->withJson([
            'tasks' => $task
        ]);

    }
}