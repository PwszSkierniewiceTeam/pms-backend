<?php

namespace PMS\Controllers\Project;

use Respect\Validation\Validator;
use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\TokenDecode\RequestingUserData;
use PMS\Models\User;
use PMS\Models\Project;
use PMS\Enums\ProjectUserRole;


class AssignUserToProjectController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        $userEmail = $request->getParsedBody();
        $userId = RequestingUserData::getUserId($request);

        $this->validator->validate($userEmail, [
            'email' => Validator::notBlank()->email()
        ]);

        if (!$this->validator->isValid()) {
            return $response->withJson($this->validator->getErrors(), 400);
        }

        $user = $this->findUserByEmail($userEmail['email']);

        if ($user == null){
            return $response->withJson(["uncategorized" => "User doesn't exist"], 404);
        }

        $project = $this->findProjectById($projectId);

        if ($project == null){
            return $response->withJson(["uncategorized" => "Project doesn't exist"], 404);
        }

        $userRole = $this->findUserRole($projectId, $userId);
        
        if ($userRole == ProjectUserRole::USER || $userRole == null){
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }

        $sql = "INSERT INTO UsersProjects (projectId , userId, role) 
                VALUES (:projectId, :userId, :userRole)";
        try {
            $stmt = $this->db->prepare($sql);
                $stmt->bindParam('projectId', $projectId);
                $stmt->bindParam('userId', $user->id);
                $stmt->bindParam('userRole', $userRole = ProjectUserRole::USER);
                $stmt->execute();
                return $response->withStatus(204);
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorize' => $e->getMessage()], 400);
            }
    }

    private function findUserByEmail(string $userEmail): ?User
    {
        $sql = "SELECT * FROM Users WHERE email=:userEmail";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("userEmail", $userEmail);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? new User($data) : null;
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