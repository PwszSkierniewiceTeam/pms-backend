<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 21.12.2018
 * Time: 20:49
 */

namespace PMS\Controllers\Tasks;


use PMS\Controllers\BaseController;
use PMS\Queries\CommonQueries;
use PMS\TokenDecode\RequestingUserData;
use Slim\Http\Request;
use Slim\Http\Response;

final class DeleteTaskController extends BaseController
{

    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $taskId = $args['taskId'];
        $userId = RequestingUserData::getUserId($request);


        if(!(CommonQueries::findTaskById($this->db, $taskId))){
            $data = ["Doesn't exist"];
            return $response->withJson($data, 401);
        }

        if(!($projectId = CommonQueries::findProjectIdByTaskId($this->db, $taskId))){
            $data = ['Unauthorized'];
            return $response->withJson($data, 401);
        }

        if(!(CommonQueries::UserInProject($this->db, $userId, $projectId))){
            $data = ['Unauthorized'];
            return $response->withJson($data, 401);
        }

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);
        //UserRole wpisane "na sztywno"
        if($userRole != "ADMIN") {
            $data = ["Insufficient privileges" ];

            return $response->withJson($data, 403);
        }

        $sql = "DELETE FROM UsersTasks WHERE taskId=:taskId;
                DELETE FROM Tasks WHERE id=:taskId;";

        $stmt=$this->db->prepare($sql);
        $stmt->bindParam("taskId", $taskId);
        $stmt->execute();

        return $response->withStatus(204);
    }
}
