<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 05.01.2019
 * Time: 12:57
 */

namespace PMS\Controllers\Tasks;


use function MongoDB\BSON\toJSON;
use PMS\Controllers\BaseController;
use PMS\Models\Task;
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


        if (!(CommonQueries::findUserRole($this->db, $projectId, $userId))) {
            return $response->withJson(["uncategorized" => "User is not assigned to this project"], 404);
        }

        $task = CommonQueries::findTaskById($this->db, $taskId);
        if (!$task) {
            return $response->withJson(["uncategorized" => "Task doesn't exist"], 401);
        }

        $task->assignedUser = $this->getUser($task->assignedUser);

        return $response->withJson([
            'task' => $task
        ]);

    }
    private function getUser($userId)
    {
        $sql = "SELECT id, name, surname FROM Users WHERE id=:userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data;
    }

}