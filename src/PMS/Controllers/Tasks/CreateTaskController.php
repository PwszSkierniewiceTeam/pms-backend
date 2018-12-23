<?php

namespace PMS\Controllers\Tasks;

use PMS\Enums\TaskStatus;
use PMS\Controllers\BaseController;
use PMS\Queries\CommonQueries;
use PMS\TokenDecode\RequestingUserData;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;



final class CreateTaskController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args = null): Response
    {
        $projectId = $request->getQueryParams()['projectId'];
        $userId = RequestingUserData::getUserId($request);

        $task = $request->getParsedBody();
        $assigned = $task['assignedUser'];


        if (!(CommonQueries::findUserRole($this->db, $userId, $projectId))) {
            $data = ['Unauthorized' => 'You are not assigned to this project'];
            return $response->withJson($data, 401);
        }

        if (!(CommonQueries::findUserById($this->db, $assigned['id']))) {
            $data = ["User doesn't exist"];
            return $response->withJson($data, 401);
        }

        if (!(CommonQueries::findUserRole($this->db, $assigned['id'], $projectId))) {
            $data = ['Unauthorized' => 'The given user is not assigned to this project'];
            return $response->withJson($data, 401);
        }

        $this->validator->validate($task, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
        ]);


        if ($this->validator->isValid()) {
            $sql = "SET @uuid = uuid();
                    INSERT INTO tasks (id, name, projectId, description, type, status)
                    VALUES        (@uuid, :name, :projectId, :description, :type, :status);
                    INSERT INTO userstasks ( taskId, userId)
                    VALUES  (@uuid, :userId);";

            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('projectId', $projectId);
                $stmt->bindParam('description', $task['description']);
                $stmt->bindParam('name', $task['name']);
                $stmt->bindParam('type', $task['type']);
                $stmt->bindParam('status', $status = TaskStatus::TODO);
                $stmt->bindParam('userId', $assigned['id']);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }
            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);

    }

}