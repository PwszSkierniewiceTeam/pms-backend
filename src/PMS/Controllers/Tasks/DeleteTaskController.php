<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 21.12.2018
 * Time: 20:49
 */

namespace PMS\Controllers\Tasks;


use PMS\Controllers\BaseController;
use PMS\Enums\ProjectUserRole;
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
            return $response->withJson(["uncategorized" => "Doesn't exist"], 404);
        }

        $projectId = CommonQueries::findProjectIdByTaskId($this->db, $taskId);

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);


        if($userRole == ProjectUserRole::USER || $userRole == null) {
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }


        $sql = " DELETE FROM Tasks WHERE id=:taskId;";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("taskId", $taskId);
            $stmt->execute();
            return $response->withStatus(204);
        }catch(\PDOException $e) {
            return $response->withJson(['uncategorize' => $e->getMessage()], 400);
        }
    }
}
