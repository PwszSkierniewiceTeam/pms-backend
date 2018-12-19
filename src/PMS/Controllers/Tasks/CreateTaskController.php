<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 11.12.2018
 * Time: 18:29
 */

namespace PMS\Controllers\Tasks;

use PMS\Enums\TaskStatus;
use PMS\Models\Task;
use PMS\Controllers\BaseController;
use PMS\TokenDecode\RequestingUserData;
use Slim\Http\Request;
use Slim\Http\Response;



class CreateTaskController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $task = new Task($request->getParsedBody());
        $projectId = $args['projectId'];
        $userId = RequestingUserData::getUserId($request);


        $this->validator->validate($task, [
            'name' => Validator::notBlank()->length(1, 30),
        ]);


        if ($this->validator->isValid()) {
            $sql = "SET @uuid = uuid();
                    INSERT INTO Tasks (id ,projectId, name, type, status) 
                    VALUES        (@uuid, :projectId, :name, :type, :status);
                    INSERT INTO UsersTasks (taskId, userId, role)
                    VALUES  (@uuid, :userId, :userRole);";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('projectId', $projectId);
                $stmt->bindParam('name', $task->name);
                $stmt->bindParam('type', $task->type);
                $stmt->bindParam('status', $status = TaskStatus::TODO);
                $stmt->bindParam('userId', $userId);
                $stmt->bindParam('userRole', $userRole = ProjectUserRole::ADMIN);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }
            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);

    }

}