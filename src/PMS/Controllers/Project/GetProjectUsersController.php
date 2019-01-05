<?php

namespace PMS\Controllers\Project;

use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\TokenDecode\RequestingUserData;
use PMS\Models\Project;
use PMS\Enums\ProjectUserRole;
use PMS\Queries\CommonQueries;


class GetProjectUsersController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userId = RequestingUserData::getUserId($request);

        $project = CommonQueries::findProjectById($this->db, $projectId);

        if ($project == null){
            return $response->withJson(["uncategorized" => "Doesn't exist"], 404);
        }

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);
        
        if ($userRole == ProjectUserRole::USER || $userRole == null){
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }

        $users = array();
        $sql = "SELECT Users.id, Users.name, Users.surname, Users.email 
                FROM Users 
                INNER JOIN UsersProjects ON Users.id = UsersProjects.userId AND UsersProjects.projectId = :projectId";
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('projectId', $projectId);
            $stmt->execute();
            
            while ($row = $stmt->fetchObject()){
                $users[] = ($row);
            }
            return $response->withJson($users, 200);
        }
        catch(\PDOException $e) {
            return $response->withJson(['uncategorize' => $e->getMessage()], 400);
        }
    }
}