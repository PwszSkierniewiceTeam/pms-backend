<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 11.12.2018
 * Time: 18:01
 */

namespace PMS\Controllers\Tasks;

use PMS\Models\Project;
use PMS\Models\Task;
use PMS\TokenDecode\RequestingUserData;
use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\Models\User;



class ListTasksController extends BaseController
{

    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userId = RequestingUserData::getUserId($request);


        // Find user in database
        $user = $this->findUserById($userId);
        // If user doesn't exists return error response
        if (!$user) {
            $data = [
                "unauthorized user" ,
            ];

            return $response->withJson($data, 401);
        }


        // Find Project in database
        $project = $this->findProjectById($projectId);
        // If project doesn't exists return error response
        if (!$project) {
            $data = [
                "unauthorized project" ,
            ];

            return $response->withJson($data, 401);
        }

        if($this->UserInProject($userId, $projectId)) {
            return $response->withJson([
                'tasks' => $this->getTasks($projectId)
            ]);
        }
    }

    private function findUserById(string $userId): ?User
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data ? new User($data) : null;
    }

    private function findProjectById(string $projectId): ?Project
    {
        $sql = "SELECT * FROM projects WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $projectId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data ? new Project($data) : null;
    }

    private function UserInProject(string $userId, string $projectId)
    {
        $sql = "SELECT * FROM usersprojects WHERE projectId=:projectId AND userId=:userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data ? true : null;
    }

    private function getTasks($projectId)
    {
        $tasks = array();
        $sql = "SELECT * FROM tasks WHERE projectId=:projectId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":projectId", $projectId);
        $stmt->execute();


        while ($row = $stmt->fetchObject()) {
            $tasks[] = new Task($row);
        }
        return $tasks;
    }


}