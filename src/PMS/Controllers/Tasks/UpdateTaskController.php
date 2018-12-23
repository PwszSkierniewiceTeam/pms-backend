<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 21.12.2018
 * Time: 18:21
 */

namespace PMS\Controllers\Tasks;


use PMS\Controllers\BaseController;
use PMS\Queries\CommonQueries;
use PMS\TokenDecode\RequestingUserData;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

final class UpdateTaskController extends BaseController
{

    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $taskId = $args['taskId'];
        $newTask = $request->getParsedBody();

        $userId = RequestingUserData::getUserId($request);
        $projectId = CommonQueries::findProjectIdByTaskId($this->db, $taskId);



        $currentTask = CommonQueries::findTaskById($this->db, $taskId);
        if (!($currentTask)) {
            $data = ["Task doesn't exist"];
            return $response->withJson($data, 401);
        }

        if (!(CommonQueries::findUserRole($this->db, $projectId, $userId))) {
            $data = ["Unauthorized"];
            return $response->withJson($data, 401);
        }

        if($assignedUser = $request->getParsedBody()['assignedUser']){
            $assignedUser =CommonQueries::findUserById($this->db, $assignedUser['id']);

            if (!(CommonQueries::findUserRole($this->db, $projectId, $assignedUser->id))) {
                $data = ["Invalid parameters" => "The given user is not assigned to this project"];
                return $response->withJson($data);
            }
        }


        $this->validator->validate($newTask, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
        ]);


        if ($this->validator->isValid()) {
            $sql = "UPDATE tasks SET name=:nname, description=:ndescription, type=:ntype, status=:nstatus
                      WHERE id=:currentId";

            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('nname', $newTask['name']);
                $stmt->bindParam('ndescription', $newTask['description']);
                $stmt->bindParam('ntype', $newTask['type']);
                $stmt->bindParam('nstatus', $newTask['status']);
                $stmt->bindParam('currentId', $taskId);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }

            if(!(CommonQueries::UserInTask($this->db, $assignedUser->id, $newTask['id'])))
            {
                $sql = "INSERT INTO userstasks (taskid, userid)
                    VALUES (:currentId, :assignedUserId)";
                try {
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam('currentId', $taskId);
                    $stmt->bindParam('assignedUserId', $assignedUser['id']);
                    $stmt->execute();
                } catch (\PDOException $e) {
                    return $response->withJson(['uncategorize' => $e->getMessage()], 400);
                }
            }
            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);

    }
}