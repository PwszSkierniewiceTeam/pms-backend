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


        $project = CommonQueries::findProjectById($this->db, $projectId);

        if (!$project) {
            return $response->withJson(["uncategorized" => "Project doesn't exist"], 401);
        }


        if(!(CommonQueries::findUserRole($this->db, $projectId, $userId))) {
            return $response->withJson(["unauthorized" => "User is not assigned to the project."],401);
        }


        return $response->withJson($this->getTasks($projectId));

    }

    private function getTasks($projectId)
    {
        $tasks = array();
        $sql = "SELECT * FROM Tasks WHERE projectId=:projectId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":projectId", $projectId);
        $stmt->execute();

        while ($row = $stmt->fetchObject()) {
            $tasks[] = new Task($row);

        }
        return $tasks;
    }
}
