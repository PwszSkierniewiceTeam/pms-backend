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

        if(!(CommonQueries::findUserById($this->db, $newTask['assignedUserId']))){
            $data = ["Invalid parameters" => "The given user doesn't exist"];
            return $response->withJson($data, 401);
        }

        if (!(CommonQueries::findUserRole($this->db, $projectId, $newTask['assignedUserId']))) {
            $data = ["Invalid parameters" => "The given user is not assigned to this project"];
            return $response->withJson($data);
        }


        $this->validator->validate($newTask, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
        ]);


        if ($this->validator->isValid()) {
            $sql = "UPDATE Tasks SET name=:name, description=:description, type=:type,
                    status =:status, assignedUserId =:assignedUserId WHERE id=:currentId";

            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('name', $newTask['name']);
                $stmt->bindParam('description', $newTask['description']);
                $stmt->bindParam('type', $newTask['type']);
                $stmt->bindParam('status', $newTask['status']);
                $stmt->bindParam('assignedUserId', $newTask['assignedUserId']);
                $stmt->bindParam('currentId', $taskId);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }

            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);

    }
}
