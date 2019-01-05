<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 11.12.2018
 * Time: 18:01
 */

namespace PMS\Controllers\Tasks;

use PMS\Models\Task;
use PMS\Queries\CommonQueries;
use PMS\TokenDecode\RequestingUserData;
use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;


final class ListTasksController extends BaseController
{

    public function handleRequest(Request $request, Response $response, array $args = null): Response
    {
        $queryParams = $request->getQueryParams();
        $projectId = $queryParams['projectId'];
        $userId = RequestingUserData::getUserId($request);


/*        $user = CommonQueries::findUserById($this->db,$userId);
        if (!$user) {
            $data = [ "User doesn't exist" ];
            return $response->withJson($data, 401);
        }

        $project = CommonQueries::findProjectById($this->db, $projectId);
        if (!$project) {
            $data = ["Project doesn't exist" ];
            return $response->withJson($data, 401);
        }*/


        /*if(!(CommonQueries::findUserRole($this->db, $userId, $projectId))) {
            $data = [
                "unauthorized" => "User is not assigned to the project.",
            ];
            return $response->withJson($data,401);
        }*/


        return $response->withJson($this->getTasks($projectId));

    }

    private function getTasks($projectId)
    {
        $all = array();
        $sql = "SELECT * FROM Tasks WHERE projectId=:projectId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":projectId", $projectId);
        $stmt->execute();

        while ($row = $stmt->fetchObject()) {
            $taskId =$row->id;
            $tasks = new Task($row);
            $users  = $this->getUsers($taskId);
            $all[] = array($tasks,$users);
        }
        return $all;
     }

    private function getUsers($taskId)
    {
        $users = array();
        $sql = "SELECT Users.id, Users.name, Users.surname FROM Users
                INNER JOIN UsersTasks
                ON Users.id = UsersTasks.userId
                WHERE UsersTasks.taskId=:taskId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":taskId", $taskId);
        $stmt->execute();

        while ($row = $stmt->fetchObject()) {
            $users[] = $row;
        }
        return $users;
    }
}
