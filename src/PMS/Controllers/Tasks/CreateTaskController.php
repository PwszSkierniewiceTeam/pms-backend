<?php

namespace PMS\Controllers\Tasks;

use PMS\Controllers\BaseController;
use PMS\Enums\TaskStatus;
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

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);

        if ($userRole == "USER" || $userRole == null) {
            return $response->withJson(['Unauthorized'], 401);
        }

        if (!(CommonQueries::findUserById($this->db, $task['assignedUser']['id']))) {
            $data = ["User doesn't exist"];
            return $response->withJson($data, 401);
        }

        if (!(CommonQueries::findUserRole($this->db, $projectId, $task['assignedUser']['id']))) {
            $data = ['Unauthorized' => 'The given user is not assigned to this project'];
            return $response->withJson($data, 401);
        }

        $this->validator->validate($task, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
        ]);


        if ($this->validator->isValid()) {
            $sql = "SET @uuid = uuid();";
            $sql .= "INSERT INTO Tasks (id, name, projectId, description, type, status, assignedUser)
                             VALUES    (@uuid, :name, :projectId, :description, :type, :status, :assignedUser);";

            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('projectId', $projectId);
                $stmt->bindParam('description', $task['description']);
                $stmt->bindParam('name', $task['name']);
                $stmt->bindParam('type', $task['type']);
                $stmt->bindParam('status', $status = TaskStatus::TODO);
                $stmt->bindParam('assignedUser', $task['assignedUser']['id']);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorized' => $e->getMessage()], 400);
            }
            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);

    }

}
