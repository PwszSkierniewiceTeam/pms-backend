<?php

namespace PMS\Controllers\Project;

use PMS\Controllers\BaseController;
use PMS\TokenDecode\RequestingUserData;
use PMS\Queries\CommonQueries;
use PMS\Enums\ProjectUserRole;
use Slim\Http\Request;
use Slim\Http\Response;

class GetProjectController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userId = (string)RequestingUserData::getUserId($request);

        $project = CommonQueries::findProjectById($this->db, $projectId);
        
        if (!CommonQueries::checkIfUserAssignedToProject($this->db, $userId, $projectId)){
            return $response->withJson(["uncategorized" => "User is not assigned to this project"], 404);
        }

        if($project == null){
            return $response->withJson(["uncategorized" => "Project doesn't exist"], 404);
        }

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);

        $project->userRole = $userRole;

        return $response->withJson($project, 200);
    }
}