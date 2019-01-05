<?php

namespace PMS\Controllers\Project;

use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\TokenDecode\RequestingUserData;
use PMS\Models\User;
use PMS\Models\Project;
use PMS\Enums\ProjectUserRole;
use PMS\Queries\CommonQueries;

class RemoveUserFromProjectCOntroller extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userToBeRmovedId = $args['userId'];
        $userId = RequestingUserData::getUserId($request);
    
        $project = CommonQueries::findProjectById($this->db, $projectId);

        if ($project == null){
            return $response->withJson(["uncategorized" => "Project doesn't exist"], 404);
        }

        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);
        
        if ($userRole == ProjectUserRole::USER || $userRole == null){
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }

        $userToBeRmovedRole = CommonQueries::findUserRole($this->db, $projectId, $userToBeRmovedId);

        if ($userToBeRmovedRole == ProjectUserRole::ADMIN){
            return $response->withJson(["uncategorized" => "Can't remove Admin"], 404);
        }

        if (!$this->checkIfUserExists($userToBeRmovedId)){
            return $response->withJson(["uncategorized" => "User doesn't exist"], 404);
        }
        
        if (!CommonQueries::checkIfUserAssignedToProject($this->db, $userToBeRmovedId, $projectId)){
            return $response->withJson(["uncategorized" => "User is not assigned to this project"], 404);
        }

        $sql = "DELETE FROM UsersProjects WHERE userId=:userId AND projectId=:projectId";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("userId", $userToBeRmovedId);
            $stmt->bindParam("projectId", $projectId);        
            $stmt->execute();

            return $response->withStatus(204);
        } catch (\PDOException $e) {
            return $response->withJson(['uncategorize' => $e->getMessage()], 400);
        }

    }

    private function checkIfUserExists(string $userId) : bool
    {
        $sql = "SELECT * FROM Users WHERE id=:userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? true : false;
    }
}