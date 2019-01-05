<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 05.01.2019
 * Time: 12:57
 */

namespace PMS\Controllers\Tasks;


use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;

class GetTaskController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args = null): Response
    {
        $queryParams = $request->getQueryParams();
        $projectId = $queryParams['projectId'];
        $userId = RequestingUserData::getUserId($request);
        $taskId = $args['taskId'];


        $user = CommonQueries::findUserById($this->db,$userId);
        if (!$user) {
            $data = [ "User doesn't exist" ];
            return $response->withJson($data, 401);
        }

        $project = CommonQueries::findProjectById($this->db, $projectId);
        if (!$project) {
            $data = ["Project doesn't exist" ];
            return $response->withJson($data, 401);
        }


        if(!(CommonQueries::findUserRole($this->db, $userId, $projectId))) {
            $data = [
                "unauthorized" => "User is not assigned to the project.",
            ];
            return $response->withJson($data,401);
        }



        return $response->withJson([
            'tasks' => $this->getTasks($projectId)
        ]);

    }

    private function getTasks($projectId)
    {
        $all = array();
        $sql = "SELECT * FROM tasks WHERE projectId=:projectId AND taskId =:taskId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":projectId", $projectId);
        $stmt->bindParam(":taskId", $taskId);
        $stmt->execute();

        $row = $stmt->fetchObject();
        $tasks = new Task($row);
        $users  = $this->getUsers($taskId);
        $all[] = array($tasks,$users);

        return $all;
    }

    private function getUsers($taskId)
    {
        $users = array();
        $sql = "SELECT users.id, users.name, users.surname FROM users
                INNER JOIN userstasks
                ON users.id = userstasks.userId
                WHERE userstasks.taskId=:taskId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":taskId", $taskId);
        $stmt->execute();

        while ($row = $stmt->fetchObject()) {
            $users[] = $row;
        }
        return $users;
    }

}