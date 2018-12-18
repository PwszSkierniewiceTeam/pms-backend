<?php

namespace PMS\Controllers\Project;

use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\TokenDecode\RequestingUserData;
use PMS\Models\Project;
use PMS\Models\User;
use PMS\Enums\ProjectUserRole;


class RemoveProjectController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userId = RequestingUserData::getUserId($request);

        $project = $this->findProjectById($projectId);

        if ($project == null){
            return $response->withJson(["uncategorized" => "Doesn't exist"], 404);
        }
            
        $userRole = $this->findUserRole($projectId, $userId);
        
        if ($userRole == ProjectUserRole::USER || $userRole == null){
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }

        $sql = "DELETE FROM UsersProjects WHERE UsersProjects.projectId=:projectId;
                DELETE FROM Projects WHERE Projects.id=:projectId;";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("projectId", $projectId);
            $stmt->execute();
            return $response->withStatus(204);
            }catch(\PDOException $e) {
                    return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }
        
    }    

    private function findProjectById(string $projectId) : ?Project
    {
        $sql = "SELECT * FROM Projects WHERE id=:projectId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? new Project($data) : null;
    }

    private function findUserRole(string $projectId, string $userId) : ?int
    {
        $sql = "SELECT UsersProjects.role as userRole FROM UsersProjects WHERE projectId=:projectId AND userId=:userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetch();
        
        return $data? intval($data['userRole']) : null;
    }
}
